<?php

namespace App\Http\Controllers;

use App\Mail\ImpayesAlertMail;
use App\Models\Clients;
use App\Models\Factures;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;
use Mail;
use stdClass;

class FactureController extends Controller
{
    public function index()
    {
        $factures = DB::table('FacturesTest')
        ->join('ClientsTest', 'FacturesTest.codeTiers', '=', 'ClientsTest.CodeTiers')
        ->select(
            'FacturesTest.idFacture',
            'FacturesTest.NumeroFacture',
            'FacturesTest.DateEntree',
            'FacturesTest.DateEcheance',
            'FacturesTest.Statut',
            'ClientsTest.Intitule as client_intitule',
            'ClientsTest.Telephone as client_telephone',
            'ClientsTest.Email as client_email'
        )
        ->orderBy('FacturesTest.dateEntree', 'desc')
        ->get();

    return view('factures.index', compact('factures'));
    }

    public function show($id){
        $facture = Factures::join('ClientsTest', 'FacturesTest.CodeTiers', '=', 'ClientsTest.CodeTiers')
            ->where('FacturesTest.idFacture', $id)
            ->select('FacturesTest.*', 'ClientsTest.Intitule', 'ClientsTest.CodeTiers')
            ->firstOrFail(); 
    
        return view('factures.show', compact('facture'));
    }

    public function create(){
        $clients = Clients::orderBy('Intitule')->get();
        return view('factures.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'CodeTiers'    => 'required|exists:ClientsTest,CodeTiers',
            'NumeroFacture'      => 'required|string|unique:FacturesTest,NumeroFacture',
            'Service'      => 'required|string|max:255',
            'ModeReglement'=> 'required|string|max:100',
            'DateEntree'   => 'required|date',
            'DateEcheance' => 'required|date|after_or_equal:DateEntree',
            'DateRemise'   => 'nullable|date|after_or_equal:DateEntree',
            'DateImpaye'   => 'nullable|date|after:DateEcheance',
            'Banque'       => 'nullable|string|max:255',
            'MontantTotal' => 'required|numeric|min:0',
            'Statut'       => 'required|string|in:Réglé,En attente,Impayé',
            'Description'  => 'nullable|string',
        ]);

        // Création
        Factures::create([
            'CodeTiers'         => $data['CodeTiers'],
            'NumeroFacture'       => $data['NumeroFacture'],
            'Service'       => $data['Service'],
            'ModeReglement' => $data['ModeReglement'],
            'DateEntree'    => $data['DateEntree'],
            'DateEcheance'  => $data['DateEcheance'],
            'DateRemise'    => $data['DateRemise'] ?? null,
            'DateImpaye'    => $data['DateImpaye'] ?? null,
            'Banque'        => $data['Banque'] ?? null,
            'MontantTotal'  => $data['MontantTotal'],
            'Statut'        => $data['Statut'],
            'Description'   => $data['Description'] ?? null,
        ]);

        return redirect()
        ->route('factures.index')
        ->with('success', 'Facture créée avec succès.');
    }

    public function edit($id){
        $facture = DB::table('FacturesTest')->where('idFacture', $id)->first();
        $clients = Clients::orderBy('Intitule')->get();
    
        return view('factures.edit', compact('facture', 'clients'));
    }

    public function update(Request $request, $id)
{
    DB::enableQueryLog();
    // 1) On récupère la facture ou on échoue
    $facture = Factures::findOrFail($id);

    // 2) Validation
    $data = $request->validate([
        'CodeTiers'       => 'required|exists:ClientsTest,CodeTiers',
        'NumeroFacture'   => 'required|string|max:100',
        'Service'         => 'required|string|max:255',
        'ModeReglement'   => 'required|string|max:100',
        'DateEntree'      => 'required|date',
        'DateEcheance'    => 'required|date|after_or_equal:DateEntree',
        'DateRemise'      => 'nullable|date|after_or_equal:DateEntree',
        'DateImpaye'      => 'nullable|date|after:DateEcheance',
        'Reference'       => 'nullable|string|max:255',
        'Libelle'         => 'nullable|string|max:255',
        'Banque'          => 'nullable|string|max:255',
        'MontantTotal'    => 'required|numeric|min:0',
        'Statut'          => 'required|string|max:50',
        'Description'     => 'nullable|string',
    ]);
    // dd([
    //     'données envoyées'      => $data,
    //     'valeurs avant update'  => $facture->only(array_keys($data)),
    //   ]);

    // 3) Mise à jour
    $facture->update($data);

    // dd(DB::getQueryLog());

    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Facture mise à jour avec succès.'
        ], 200);
    }

    return redirect()
        ->route('factures.index')
        ->with('success', 'Facture mise à jour avec succès.');
}


    public function destroy($id){
        DB::table('FacturesTest')
        ->where('idFacture', $id)
        ->delete();

        return back()->with('success', 'Facture supprimée avec succès.');
    }


    public function notify($id){
        $facture = DB::table('FacturesTest')
            ->join('ClientsTest', 'FacturesTest.CodeTiers', '=', 'ClientsTest.CodeTiers')
            ->select(
                'FacturesTest.*',
                'ClientsTest.Email as client_email',
                'ClientsTest.Intitule as client_intitule',
                'ClientsTest.CodeTiers as client_code'
            )
            ->where('FacturesTest.idFacture', $id)
            ->first();

        if (!$facture) {
            return back()->with('error', 'Facture introuvable.');
        }

        // Création d’un client “virtuel” (stdClass)
        $client = new stdClass();
        $client->CodeTiers = $facture->client_code;
        $client->Intitule = $facture->client_intitule;
        $client->Email = $facture->client_email;
        
        // Préparation de la collection contenant une seule facture
        $factureObj = new Factures((array) $facture); // on cast en array pour hydrater le modèle
        $factures = collect([$factureObj]);
        
        if (!empty($client->Email)) {
            Mail::to($client->Email)
                ->queue(new ImpayesAlertMail($client, $factures));
        
            Log::info("Email envoyé à {$client->Email}");
        
            return back()->with('success', "E-mail de relance envoyé à {$client->Email}");
        } else {
            Log::warning("Le client {$client->CodeTiers} n’a pas d’adresse e-mail.");
            return back()->with('error', "Le client n’a pas d’adresse e-mail.");
        }
}

    
}

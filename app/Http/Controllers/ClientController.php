<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use DB;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(){
        $clients = Clients::all(); 
        return view('clients.index', compact('clients'));
    }
    

    // Affiche le détail d’un client + ses factures
    public function show($CodeC)
    {
        $client = Clients::join('FacturesTest', 'ClientsTest.CodeTiers', '=', 'FacturesTest.CodeTiers')
            ->where('ClientsTest.CodeTiers', $CodeC)
            ->select(
                'ClientsTest.CodeTiers',
                'ClientsTest.Intitule',
                'ClientsTest.Adresse',
                'ClientsTest.Telephone',
                'ClientsTest.Email',
                'FacturesTest.idFacture',
                'FacturesTest.NumeroFacture', 
                'FacturesTest.Libelle'
            )
            ->firstOrFail();
    
        return view('clients.show', compact('client'));
    }

    public function create(){
        return view('clients.create');
    }

    public function store(Request $request){
        $data = $request->validate([
            'CodeTiers'    => 'required|string|max:20|unique:ClientsTest,CodeTiers',
            'Intitule' => 'required|string|max:255',
            'Adresse'  => 'required|string',
            'Telephone'=> 'required|string|max:20',
            'Email'    => 'required|email|unique:ClientsTest,email',
        ]);

        Clients::create($data);

        return redirect()->route('clients.index')
                         ->with('success', 'Client créé avec succès.');
    }

    public function destroy($CodeTiers){
    // Supprimer d'abord toutes les factures associées
    DB::table('FacturesTest')
        ->where('CodeTiers', $CodeTiers)
        ->delete();

    // Puis supprimer le client
    DB::table('ClientsTest')
        ->where('CodeTiers', $CodeTiers)
        ->delete();

    return redirect()
        ->route('clients.index')
        ->with('success', 'Client et factures associées supprimés.');
    }

    public function edit($CodeC)
    {
        $client = Clients::findOrFail($CodeC);
        return view('clients.edit', compact('client'));
    }

    // 6. Met à jour le client dans la BDD
    public function update(Request $request, $CodeC)
    {
        $client = Clients::findOrFail($CodeC);

        $data = $request->validate([
            'Intitule'  => 'required|string|max:255',
            'Adresse'   => 'required|string',
            'Telephone' => 'required|string|max:20',
            'Email'     => 'required|email|unique:ClientsTest,email,' . $client->CodeTiers . ',CodeTiers',
        ]);
        $client->update($data);

        return redirect()->route('clients.index', $client->CodeTiers)
                         ->with('success', 'Client mis à jour avec succès.');
    }

}

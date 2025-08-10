<?php

namespace App\Console\Commands;

use App\Mail\ImpayesAlertMail;
use App\Models\Clients;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;
use Mail;

class SendImpayesAlerts extends Command
{
    protected $signature = 'alerts:impayes';
    protected $description = 'Envoie un e-mail aux clients ayant des factures impayées';

    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
    
        // Récupérer toutes les factures impayées en retard avec les infos client
        $rows = DB::table('FacturesTest')
            ->join('ClientsTest', 'FacturesTest.CodeTiers', '=', 'ClientsTest.CodeTiers')
            ->where('FacturesTest.Statut', 'Impayee')
            ->where('FacturesTest.DateEcheance', '<', $today)
            ->select(
                'ClientsTest.CodeTiers',
                'ClientsTest.Nom as client_nom',
                'ClientsTest.Email',
                'FacturesTest.IdFacture',
                'FacturesTest.NumeroFacture',
                'FacturesTest.DateEntree',
                'FacturesTest.DateEcheance',
                'FacturesTest.Banque',
                'FacturesTest.MontantTotal'
            )
            ->get();
    
        // Grouper les FacturesTest par client
        $clients = [];
    
        foreach ($rows as $row) {
            $clients[$row->CodeTiers]['Email'] = $row->email;
            $clients[$row->CodeTiers]['Nom'] = $row->client_nom;
            $clients[$row->CodeTiers]['FacturesTest'][] = $row;
        }
    
        // Envoyer un email par client
        foreach ($clients as $clientId => $clientData) {
            Mail::to($clientData['Email'])
                ->queue(new ImpayesAlertMail((object)[
                    'CodeTiers' => $clientId,
                    'nom' => $clientData['Nom'],
                    'email' => $clientData['Email']
                ], collect($clientData['FacturesTest'])));
    
            $this->info("Email envoyé à {$clientData['email']}");
        }
    
        return 0;
    }
}

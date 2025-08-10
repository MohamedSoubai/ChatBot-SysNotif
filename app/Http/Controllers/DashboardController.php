<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\Factures;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ── 1) Totaux globaux (inchangé) ────────────────────────────────────────────
        $totalInvoices   = Factures::count();
        $totalRevenue    = Factures::sum('MontantTotal');
        $paidCount       = Factures::where('Statut', 'Réglé')->count();
        $unpaidCount     = Factures::where('Statut', 'Impayé')->count();
        $pendingCount    = Factures::where('Statut', 'En attente')->count();

        // ── 2) Évolution mensuelle des montants (inchangé) ─────────────────────────
        $months         = [];
        $monthlyTotals  = [];

        for ($i = 11; $i >= 0; $i--) {
            $date          = Carbon::now()->subMonths($i);
            $months[]      = $date->translatedFormat('M Y');

            $startOfMonth  = $date->copy()->startOfMonth()->toDateString();
            $endOfMonth    = $date->copy()->endOfMonth()->toDateString();

            $sum = Factures::whereBetween('DateEntree', [$startOfMonth, $endOfMonth])
                           ->sum('MontantTotal');

            $monthlyTotals[] = $sum;
        }

        // ── 3) Répartition des factures par statut (NOUVEAU) ─────────────────────────
        // Définir la liste des statuts qu’on veut afficher
        $statusLabels = ['Réglé', 'En attente', 'Impayé'];
        // Calculer le nombre de factures pour chacun
        $statusData   = [];
        foreach ($statusLabels as $label) {
            $statusData[] = Factures::where('Statut', $label)->count();
        }

        // ── 4) Passer tout à la vue ──────────────────────────────────────────────────
        return view('dashboard', compact(
            'totalInvoices',
            'totalRevenue',
            'paidCount',
            'unpaidCount',
            'pendingCount',
            'months',
            'monthlyTotals',
            'statusLabels',
            'statusData'
        ));
    }
}

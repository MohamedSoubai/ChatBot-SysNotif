{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    
    {{-- ─────────── 1) En-tête de page + Breadcrumb ─────────── --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt text-primary me-2"></i>
                Tableau de bord
            </h1>
            <nav aria-label="breadcrumb" class="mt-1">
                <ol class="breadcrumb bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- ─────────── Fin header ─────────── --}}

    {{-- ─────────── 2) Row de cards d’indicateurs ─────────── --}}
    <div class="row">

        <!-- Total Factures -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body px-3 py-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Factures
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalInvoices }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chiffre d'Affaires Total -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body px-3 py-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                CA Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalRevenue, 2, ',', ' ') }} DH
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Factures Réglées -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body px-3 py-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Réglées
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $paidCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Factures Impayées / En attente -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body px-3 py-3">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Impayées / En attente
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $unpaidCount }} / {{ $pendingCount }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- ─────────── Fin row cards ─────────── --}}

    {{-- ─────────── 3) Deux graphiques côte à côte ─────────── --}}
    <div class="row">
        <!-- 3.a) Carte “Revenu Mensuel” (line chart) --> 
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenu Mensuel (6 derniers mois)</h6>
                    <small class="text-muted">{{ now()->translatedFormat('F Y') }}</small>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3.b) Carte “Répartition par Statut” (pie chart) -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Répartition Factures par Statut</h6>
                    <small class="text-muted">Total: {{ $totalInvoices }}</small>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-2">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ─────────── Fin row graphiques ─────────── --}}

</div>
@endsection

@push('scripts')
    <!-- Charger Chart.js depuis le CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script pour dessiner les graphiques -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ─────────── a) Revenu Mensuel (line chart) ───────────
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                        label: 'Montant Total (DH)',
                        data: {!! json_encode($monthlyTotals) !!},
                        borderColor: 'rgba(78, 115, 223, 1)',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointBorderColor: 'rgba(78, 115, 223, 1)',
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                        pointHoverBorderColor: 'rgba(78, 115, 223, 1)'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { maxRotation: 0, minRotation: 0 }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // ─────────── b) Répartition Factures par Statut (pie chart) ───────────
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($statusLabels) !!},
                    datasets: [{
                        data: {!! json_encode($statusData) !!},
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.7)',   // vert → Réglé
                            'rgba(255, 193, 7, 0.7)',   // jaune → En attente
                            'rgba(220, 53, 69, 0.7)'    // rouge → Impayé
                        ],
                        hoverBackgroundColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(220, 53, 69, 1)'
                        ],
                        hoverBorderColor: 'rgba(234, 236, 244, 1)'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush

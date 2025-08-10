{{-- resources/views/factures/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Factures')

@push('styles')
    <!-- On retire Bulma et DataTables Bulma, on utilise Bootstrap 4/DataTables intégré à SB Admin 2 -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bulma/1.0.3/css/bulma.min.css"
    >
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    >
    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/2.3.1/css/dataTables.bulma.css"
    >
    <style>
        /* — Champ de recherche style “SB Admin 2” — */
        .search-input {
            position: relative;
            max-width: 300px;
        }
        .search-input .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #4e73df;
            font-size: 0.9rem;
            pointer-events: none;
        }
        .search-input input {
            padding-left: 2rem;
        }

        /* — Sélecteur statut stylé (bord arrondi) — */
        .status-select {
            border-radius: 50rem;
            padding: 0.375rem 1rem;
            border: 1px solid rgba(33, 150, 243, .25);
            background: rgba(33, 150, 243, .07);
            backdrop-filter: blur(4px);
            transition: .3s;
        }
        .status-select:focus {
            border-color: #ff9800;
            box-shadow: 0 0 0 .15rem rgba(255, 152, 0, .25);
        }

        /* — Badges “pill” dégradés — */
        .badge-pill {
            border-radius: 50rem;
            font-weight: 600;
            color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .08);
            padding: .35em .9em;
        }
        .badge-regle   { background: linear-gradient(135deg, #4caf50, #66bb6a); }
        .badge-attente { background: linear-gradient(135deg, #ff9800, #ffb74d); }
        .badge-impaye  { background: linear-gradient(135deg, #f44336, #e57373); }

        /* — Hover sur les lignes — */
        #facturesTable tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.04);
        }

        /* — Boutons icônes actions — */
        .action-btn {
            background: none;
            border: none;
            padding: 0;
            font-size: 1rem;
            color: inherit;
            transition: transform 0.2s;
        }
        .action-btn:hover {
            transform: scale(1.1);
        }

        /* — Modal Bulma : conserver largeur max — */
        .modal-card {
            width: 90%;
            max-width: 800px;
        }
        .modal-body-custom {
            padding: 1.5rem;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid mb-5">
    <!-- Page Heading / Actions -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fa fa-file-text text-primary"></i>
                <span class="ms-2">Factures</span>
            </h1>
            <p class="text-gray-600 mb-0">Suivi & relances client</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <!-- Champ recherche “pill” -->
            <div class="search-input">
                <span class="input-icon"><i class="fa fa-search"></i></span>
                <input
                    id="globalSearch"
                    type="text"
                    class="form-control form-control-sm"
                    placeholder="Recherche rapide"
                >
            </div>
            <!-- Sélecteur statut -->
            <select id="statusFilter" class="form-select form-select-sm status-select">
                <option value="">Tous statuts</option>
                <option value="Réglé">✔ Réglé</option>
                <option value="En attente">⏳ En attente</option>
                <option value="Impayé">❌ Impayé</option>
            </select>
            <!-- Bouton “Ajouter” -->
            <button
                type="button"
                class="btn btn-primary btn-sm rounded-pill"
                onclick="openCreateModal('{{ route('factures.create') }}')"
            >
                <i class="fa fa-plus me-1"></i>
                <span>Ajouter</span>
            </button>
        </div>
    </div>

    @includeIf('partials.alerts')

    <!-- Table des factures -->
    <div class="card shadow border-0">
        <div class="card-body px-3 py-3">
            <div class="table-responsive">
                <table id="facturesTable" class="table table-bordered table-hover table-striped mb-0">
                    <thead class="thead-light text-secondary text-uppercase small">
                        <tr>
                            <th scope="col" class="px-3 py-2">#</th>
                            <th scope="col" class="px-3 py-2">Client</th>
                            <th scope="col" class="px-3 py-2">Date entrée</th>
                            <th scope="col" class="px-3 py-2">Échéance</th>
                            <th scope="col" class="px-3 py-2">Statut</th>
                            <th scope="col" class="px-3 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($factures as $f)
                            @php
                                $badgeClass = match($f->Statut) {
                                    'Réglé'      => 'badge-regle',
                                    'En attente' => 'badge-attente',
                                    'Impayé'     => 'badge-impaye',
                                    default      => ''
                                };
                            @endphp
                            <tr>
                                <!-- # : ouvre modal Show -->
                                <td class="px-3 py-2">
                                    <button
                                        class="btn btn-link btn-sm text-primary fact-detail p-0"
                                        data-id="{{ $f->idFacture }}"
                                    >
                                        <strong>{{ $f->NumeroFacture }}</strong>
                                    </button>
                                </td>
                                <td class="px-3 py-2">{{ $f->client_intitule }}</td>
                                <td class="px-3 py-2">
                                    {{ $f->DateEntree
                                        ? \Carbon\Carbon::parse($f->DateEntree)->translatedFormat('d M Y')
                                        : '—' }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ $f->DateEcheance
                                        ? \Carbon\Carbon::parse($f->DateEcheance)->translatedFormat('d M Y')
                                        : '—' }}
                                </td>
                                <td class="px-3 py-2">
                                    <span class="badge-pill {{ $badgeClass }}">{{ $f->Statut }}</span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <!-- Bouton “Notifier” -->
                                    <form
                                        action="{{ route('factures.notify', $f->idFacture) }}"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="btn btn-outline-primary btn-sm action-btn"
                                            data-bs-toggle="tooltip"
                                            title="Envoyer un rappel"
                                        >
                                            <i class="fa fa-bell-o"></i>
                                        </button>
                                    </form>

                                    <!-- Bouton “Éditer” -->
                                    <button
                                        type="button"
                                        class="btn btn-link btn-sm text-warning action-btn ms-2"
                                        onclick="openEditModal('{{ route('factures.edit', $f->idFacture) }}')"
                                        data-bs-toggle="tooltip"
                                        title="Éditer"
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </button>

                                    <!-- Bouton “Supprimer” -->
                                    <form
                                        action="{{ route('factures.destroy', $f->idFacture) }}"
                                        method="POST"
                                        class="d-inline ms-2"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="btn btn-link btn-sm text-danger action-btn"
                                            title="Supprimer"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="thead-light text-secondary text-uppercase small">
                        <tr>
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">Client</th>
                            <th class="px-3 py-2">Date entrée</th>
                            <th class="px-3 py-2">Échéance</th>
                            <th class="px-3 py-2">Statut</th>
                            <th class="px-3 py-2 text-center">Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals Bulma pour CREATE / EDIT / SHOW -->
{{-- Modal CREATE --}}
<div id="factureCreateModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">
                <span class="icon-text">
                    <span class="icon has-text-primary">
                        <i class="fa fa-plus-square"></i>
                    </span>
                    <span>Ajouter une facture</span>
                </span>
            </p>
            <button
                class="delete"
                aria-label="Fermer"
                id="modalCloseCreateBtnFacture"
            ></button>
        </header>
        <section class="modal-card-body modal-body-custom">
            {{-- Formulaire de création injecté ici --}}
        </section>
    </div>
</div>

{{-- Modal EDIT --}}
<div id="factureEditModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">
                <span class="icon-text">
                    <span class="icon has-text-warning">
                        <i class="fa fa-pencil-square"></i>
                    </span>
                    <span>Modifier la facture</span>
                </span>
            </p>
            <button
                class="delete"
                aria-label="Fermer"
                id="modalCloseEditBtnFacture"
            ></button>
        </header>
        <section class="modal-card-body modal-body-custom">
            {{-- Formulaire d’édition injecté ici --}}
        </section>
    </div>
</div>

{{-- Modal SHOW --}}
<div id="factureShowModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">
                <span class="icon-text">
                    <span class="icon has-text-link">
                        <i class="fa fa-file-text"></i>
                    </span>
                    <span>Détails de la facture</span>
                </span>
            </p>
            <button
                class="delete"
                aria-label="Fermer"
                id="modalCloseShowBtnFacture"
            ></button>
        </header>
        <section class="modal-card-body modal-body-custom">
            {{-- Détails (factures/show.blade.php) injectés ici --}}
        </section>
    </div>
</div>
@endsection

@push('scripts')
    {{-- 1) jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    {{-- 2) DataTables Bulma --}}
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bulma.js"></script>

    <script>
        $(document).ready(function () {
            // ── 1) Instanciation de DataTables ──────────────────────────────────────────
            const table = $('#facturesTable').DataTable({
                responsive: {
                    details: { type: 'column', target: 'tr' }
                },
                columnDefs: [
                    { className: 'dtr-control', orderable: false, targets: 0 },
                    { orderable: false, targets: 5 }
                ],
                order: [[2, 'desc']],      // Tri initial sur "Date entrée"
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                searching: true,
                info: false,
            });

            // ── 2) Recherche “pill” (champ externe) ─────────────────────────────────────
            $('#globalSearch').on('keyup', function () {
                table.search($(this).val()).draw();
            });

            // ── 3) Filtre par “Statut” (select externe) ────────────────────────────────
            $('#statusFilter').on('change', function () {
                table
                    .column(4)               // Cinquième colonne = "Statut"
                    .search($(this).val(), false, false)
                    .draw();
            });

            // ── 4) Clic sur un numéro de facture : affichage modal SHOW + AJAX ─────────
            $('#facturesTable').on('click', '.fact-detail', function () {
                const idFacture = $(this).data('id');
                const urlShow = '{{ url("factures") }}/' + idFacture;

                // Spinner dans le modal
                $('#factureShowModal .modal-card-body').html(`
                    <div class="has-text-centered p-5">
                        <span class="icon is-large">
                            <i class="fa fa-spinner fa-pulse fa-2x"></i>
                        </span>
                    </div>
                `);

                // Ouvrir modal SHOW
                $('#factureShowModal').addClass('is-active');

                $.ajax({
                    url: urlShow,
                    type: 'GET',
                    dataType: 'html',
                    success(htmlFragment) {
                        $('#factureShowModal .modal-card-body').html(htmlFragment);
                    },
                    error() {
                        $('#factureShowModal .modal-card-body').html(`
                            <div class="notification is-danger">
                                Impossible de charger les détails de la facture.
                            </div>
                        `);
                    }
                });
            });

            // ── 5) Fermeture des modals Bulma ────────────────────────────────────────────
            $('#modalCloseShowBtnFacture, #modalCloseEditBtnFacture, #modalCloseCreateBtnFacture, .modal-background').on('click', function () {
                $('#factureCreateModal, #factureEditModal, #factureShowModal').removeClass('is-active');
            });

            // ── 6) Ouvrir modal EDIT (comme SHOW) ───────────────────────────────────────
            window.openEditModal = function (url) {
                // Afficher spinner
                $('#factureEditModal .modal-card-body').html(`
                    <div class="has-text-centered p-5">
                        <span class="icon is-large">
                            <i class="fa fa-spinner fa-pulse fa-2x"></i>
                        </span>
                    </div>
                `);

                // Ouvrir modal EDIT
                $('#factureEditModal').addClass('is-active');

                // AJAX pour charger partial edit.blade.php
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    success(htmlFragment) {
                        $('#factureEditModal .modal-card-body').html(htmlFragment);
                    },
                    error() {
                        $('#factureEditModal .modal-card-body').html(`
                            <div class="notification is-danger">
                                Impossible de charger le formulaire d’édition.
                            </div>
                        `);
                    }
                });
            };

            // ── 7) Ouvrir modal CREATE ───────────────────────────────────────────────────
            window.openCreateModal = function (url) {
                $('#factureCreateModal .modal-card-body').html(`
                    <div class="has-text-centered p-5">
                        <span class="icon is-large">
                            <i class="fa fa-spinner fa-pulse fa-2x"></i>
                        </span>
                    </div>
                `);

                $('#factureCreateModal').addClass('is-active');

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'html',
                    success(htmlFragment) {
                        $('#factureCreateModal .modal-card-body').html(htmlFragment);
                    },
                    error() {
                        $('#factureCreateModal .modal-card-body').html(`
                            <div class="notification is-danger">
                                Impossible de charger le formulaire de création.
                            </div>
                        `);
                    }
                });
            };
        });
    </script>
@endpush


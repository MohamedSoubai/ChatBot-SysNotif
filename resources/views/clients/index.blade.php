@extends('layouts.app')
@section('title', 'Clients')

@push('styles')
    <!-- Suppression des CSS Bulma et UIkit, on utilise uniquement les styles SB Admin 2 / Bootstrap 4 inclus dans le layout -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/css/uikit.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.uikit.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Petite personnalisation pour l’effet hover sur les lignes de tableau */
        #clientsTable tbody tr:hover {
            background-color: #f8f9fc;
        }
        .action-icons .btn {
            font-size: 1.1rem;
            transition: transform 0.2s;
        }
        .action-icons .btn:hover {
            transform: scale(1.1);
        }
        .btn-refresh {
            background: transparent;
            border: none;
            color: #858796;
            transition: transform 0.2s;
        }
        .btn-refresh:hover {
            color: #4e73df;
            transform: rotate(90deg);
        }
        /* Ajustement pour que le contenu injecté via AJAX conserve du padding SB Admin 2 */
        .modal-body-custom {
            padding: 1.5rem;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid mb-5">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-people text-primary"></i>
            Gestion des clients
        </h1>
        <button type="button"
                class="btn btn-primary btn-sm rounded-pill"
                onclick="openCreateModal('{{ route('clients.create') }}')">
            <i class="bi bi-plus-lg"></i>
            <span class="ms-1">Ajouter un client</span>
        </button>
    </div>
    <p class="mb-4 text-gray-600">Vue centralisée des tiers et contacts enregistrés.</p>

    @includeIf('partials.alerts')

    <!-- DataTales Example -->
    <div class="card shadow mb-4 border-0">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary text-uppercase small">Tableau des clients</h6>
            <button class="btn-refresh" onclick="location.reload()" title="Rafraîchir">
                <i class="bi bi-arrow-clockwise fs-5"></i>
            </button>
        </div>
        <div class="card-body px-3 py-3">
            <div class="table-responsive">
                <table id="clientsTable" class="table table-bordered table-hover table-striped mb-0">
                    <thead class="thead-light text-secondary text-uppercase small">
                        <tr>
                            <th scope="col" class="px-3 py-2">Code</th>
                            <th scope="col" class="px-3 py-2">Intitulé</th>
                            <th scope="col" class="px-3 py-2">Téléphone</th>
                            <th scope="col" class="px-3 py-2">Email</th>
                            <th scope="col" class="px-3 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <td class="fw-bold text-primary px-3 py-2">{{ $client->CodeTiers }}</td>
                                <td class="px-3 py-2">{{ $client->Intitule }}</td>
                                <td class="px-3 py-2 text-muted">
                                    <i class="bi bi-telephone me-1"></i>{{ $client->Telephone ?: '—' }}
                                </td>
                                <td class="px-3 py-2 text-muted">
                                    <i class="bi bi-envelope me-1"></i>{{ $client->Email ?: '—' }}
                                </td>
                                <td class="text-center px-3 py-2 action-icons">
                                    <!-- Voir -->
                                    <button type="button"
                                            class="btn btn-link text-info p-0 me-2"
                                            onclick="openShowModal('{{ route('clients.show', $client->CodeTiers) }}')"
                                            title="Voir">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <!-- Éditer -->
                                    <button type="button"
                                            class="btn btn-link text-warning p-0 me-2"
                                            onclick="openEditModal('{{ route('clients.edit', $client->CodeTiers) }}')"
                                            title="Éditer">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <!-- Supprimer -->
                                    <form action="{{ route('clients.destroy', $client->CodeTiers) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-link text-danger p-0"
                                                title="Supprimer">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucun client trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modals Bulma (on conserve le fonctionnement JS existant, on adapte juste un peu le padding) -->

{{-- Modal Bulma pour CREATE --}}
<div id="clientCreateModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Ajouter un nouveau client</p>
            <button id="modalCloseCreateBtn" class="delete" aria-label="Fermer"></button>
        </header>
        <section class="modal-card-body modal-body-custom">
            {{-- Le formulaire de création (clients/create.blade.php) sera injecté ici --}}
        </section>
    </div>
</div>

{{-- Modal Bulma pour EDIT --}}
<div id="clientEditModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Modifier le client</p>
            <button id="modalCloseEditBtn" class="delete" aria-label="Fermer"></button>
        </header>
        <section class="modal-card-body modal-body-custom">
            {{-- Le formulaire d’édition (clients/edit.blade.php) sera injecté ici --}}
        </section>
    </div>
</div>

{{-- Modal Bulma pour SHOW --}}
<div id="clientShowModal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Détails du client</p>
            <button id="modalCloseShowBtn" class="delete" aria-label="Fermer"></button>
        </header>
        <section class="modal-card-body modal-body-custom">
            {{-- Les détails (clients/show.blade.php) seront injectés ici --}}
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/js/uikit.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.uikit.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            new DataTable('#clientsTable', {
                info: false,
                responsive: true,
                pageLength: 10,
                language: {
                    emptyTable: "Aucun client à afficher",
                    search: "Rechercher :",
                    paginate: { previous: "Précédent", next: "Suivant" }
                }
            });

            // Fermeture des modals
            $('#modalCloseCreateBtn, #modalCloseEditBtn, #modalCloseShowBtn, .modal-background').on('click', () => {
                $('#clientCreateModal, #clientEditModal, #clientShowModal').removeClass('is-active');
            });
        });

        // 1) Ouvrir modal CREATE
        function openCreateModal(url) {
            $('#clientCreateModal').addClass('is-active');
            $('#clientCreateModal .modal-card-body').html(`
                <div class="has-text-centered py-6">
                    <progress class="progress is-small is-primary" max="100">Chargement</progress>
                    <p class="mt-2">Chargement du formulaire...</p>
                </div>
            `);
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                success(html) {
                    $('#clientCreateModal .modal-card-body').html(html);
                },
                error() {
                    $('#clientCreateModal .modal-card-body').html(`
                        <div class="notification is-danger is-light">
                            Impossible de charger le formulaire de création.
                        </div>
                    `);
                }
            });
        }

        // 2) Ouvrir modal EDIT
        function openEditModal(url) {
            $('#clientEditModal').addClass('is-active');
            $('#clientEditModal .modal-card-body').html(`
                <div class="has-text-centered py-6">
                    <progress class="progress is-small is-link" max="100">Chargement</progress>
                    <p class="mt-2">Chargement du formulaire...</p>
                </div>
            `);
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                success(html) {
                    $('#clientEditModal .modal-card-body').html(html);
                },
                error() {
                    $('#clientEditModal .modal-card-body').html(`
                        <div class="notification is-danger is-light">
                            Échec du chargement du formulaire d’édition.
                        </div>
                    `);
                }
            });
        }

        // 3) Ouvrir modal SHOW
        function openShowModal(url) {
            $('#clientShowModal').addClass('is-active');
            $('#clientShowModal .modal-card-body').html(`
                <div class="has-text-centered py-6">
                    <progress class="progress is-small is-info" max="100">Chargement</progress>
                    <p class="mt-2">Chargement des détails...</p>
                </div>
            `);
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                success(html) {
                    $('#clientShowModal .modal-card-body').html(html);
                },
                error() {
                    $('#clientShowModal .modal-card-body').html(`
                        <div class="notification is-danger is-light">
                            Impossible de charger les détails du client.
                        </div>
                    `);
                }
            });
        }
    </script>
@endpush

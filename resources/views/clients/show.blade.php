{{-- Fragment AJAX chargé dans la modal Bulma (Afficher un client) --}}

<header class="modal-card-head">
    <p class="modal-card-title">
        <span class="icon-text">
            <span class="icon has-text-link">
                <i class="bi bi-person-lines-fill"></i>
            </span>
            <span>Détails du client #{{ $client->CodeTiers }}</span>
        </span>
    </p>
</header>

<section class="modal-card-body">
    <div class="content">

        {{-- Code client --}}
        <p>
            <strong>Code client :</strong>
            <span class="has-text-weight-medium">{{ $client->CodeTiers }}</span>
        </p>

        {{-- Intitulé --}}
        <p>
            <strong>Intitulé :</strong>
            <span class="has-text-weight-medium">{{ $client->Intitule }}</span>
        </p>

        {{-- Adresse --}}
        <p>
            <strong>Adresse :</strong>
            <span class="has-text-weight-medium">{{ $client->Adresse ?: '—' }}</span>
        </p>

        {{-- Téléphone --}}
        <p>
            <strong>Téléphone :</strong>
            <span class="has-text-weight-medium">{{ $client->Telephone ?: '—' }}</span>
        </p>

        {{-- Email --}}
        <p>
            <strong>Email :</strong>
            <span class="has-text-weight-medium">{{ $client->Email ?: '—' }}</span>
        </p>

    </div>

</section>

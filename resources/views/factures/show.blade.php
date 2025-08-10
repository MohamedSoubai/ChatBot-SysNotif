{{-- Fragment AJAX chargé dans la modal Bulma --}}

<header class="modal-card-head">
    <p class="modal-card-title">
        <span class="icon-text">
            <span class="icon has-text-primary">
                <i class="fa fa-file-text-o"></i>
            </span>
            <span>Facture #{{ $facture->NumeroFacture }}</span>
        </span>
    </p>
</header>

<section class="modal-card-body">
    <div class="content">
        <p>
            <strong>Client :</strong>
            <a href="{{ route('clients.show', $facture->CodeTiers) }}" class="has-text-link">
                {{ $facture->Intitule }}
            </a>
        </p>

        <p><strong>Service :</strong> {{ $facture->Service }}</p>
        <p><strong>Mode de règlement :</strong> {{ $facture->ModeReglement }}</p>

        @php
            $dateEntree    = $facture->DateEntree    ? \Carbon\Carbon::parse($facture->DateEntree)->translatedFormat('d M Y')    : '—';
            $dateEcheance  = $facture->DateEcheance  ? \Carbon\Carbon::parse($facture->DateEcheance)->translatedFormat('d M Y')  : '—';
            $dateRemise    = $facture->DateRemise    ? \Carbon\Carbon::parse($facture->DateRemise)->translatedFormat('d M Y')    : null;
            $dateImpaye    = $facture->DateImpaye    ? \Carbon\Carbon::parse($facture->DateImpaye)->translatedFormat('d M Y')    : null;
        @endphp

        <p><strong>Date d’entrée :</strong> {{ $dateEntree }}</p>
        <p><strong>Échéance :</strong> {{ $dateEcheance }}</p>

        @if($dateRemise)
            <p><strong>Date de remise :</strong> {{ $dateRemise }}</p>
        @endif

        @if($dateImpaye)
            <p><strong>Date d’impayé :</strong> {{ $dateImpaye }}</p>
        @endif

        <p><strong>Banque :</strong> {{ $facture->Banque }}</p>
        <p>
            <strong>Montant :</strong>
            {{ number_format($facture->MontantTotal, 2, ',', ' ') }} €
        </p>

        @php
            $tagClass = match($facture->Statut) {
                'Réglé' => 'is-success',
                'En attente' => 'is-warning',
                'Impayé' => 'is-danger',
                default => 'is-light'
            };
        @endphp

        <p>
            <strong>Statut :</strong>
            <span class="tag {{ $tagClass }} is-rounded is-medium">
                {{ $facture->Statut }}
            </span>
        </p>

        @if($facture->Description)
            <hr>
            <p><strong>Description :</strong></p>
            <p class="has-text-grey-dark">{{ $facture->Description }}</p>
        @endif
    </div>
</section>


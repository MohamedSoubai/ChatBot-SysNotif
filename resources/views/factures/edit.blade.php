{{-- Fragment AJAX pour modifier une facture --}}

<header class="modal-card-head">
    <p class="modal-card-title">
        <span class="icon-text">
            <span class="icon has-text-warning">
                <i class="bi bi-pencil-square"></i>
            </span>
            <span>Modifier la facture #{{ $facture->NumeroFacture }}</span>
        </span>
    </p>
</header>

<section class="modal-card-body">
    <form action="{{ route('factures.update', $facture->IdFacture) }}" method="POST" id="editFactureForm">
        @csrf
        @method('PUT')

        {{-- Client (select) --}}
        <div class="field">
            <label for="CodeTiers" class="label">Client</label>
            <div class="control has-icons-left">
                <div class="select @error('CodeTiers') is-danger @enderror">
                    <select id="CodeTiers" name="CodeTiers" required>
                        <option value="">Sélectionnez un client</option>
                        @foreach($clients as $c)
                            <option 
                                value="{{ $c->CodeTiers }}" 
                                {{ old('CodeTiers', $facture->CodeTiers) == $c->CodeTiers ? 'selected' : '' }}>
                                {{ $c->Intitule }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="bi bi-people-fill"></i>
                </span>
            </div>
            @error('CodeTiers')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Numéro Facture (readonly) --}}
        <div class="field">
            <label for="NumeroFacture" class="label">Numéro de facture</label>
            <div class="control has-icons-left">
                <input
                    type="text"
                    id="NumeroFacture"
                    name="NumeroFacture"
                    class="input is-static"
                    value="{{ $facture->NumeroFacture }}"
                    readonly>
                <span class="icon is-small is-left">
                    <i class="bi bi-hash"></i>
                </span>
            </div>
        </div>

        {{-- Service --}}
        <div class="field">
            <label for="Service" class="label">Service</label>
            <div class="control has-icons-left">
                <input
                    type="text"
                    id="Service"
                    name="Service"
                    class="input @error('Service') is-danger @enderror"
                    value="{{ old('Service', $facture->Service) }}"
                    placeholder="Description du Service"
                    required>
                <span class="icon is-small is-left">
                    <i class="bi bi-gear-fill"></i>
                </span>
            </div>
            @error('Service')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mode de règlement --}}
        <div class="field">
            <label for="ModeReglement" class="label">Mode de règlement</label>
            <div class="control has-icons-left">
                <input
                    type="text"
                    id="ModeReglement"
                    name="ModeReglement"
                    class="input @error('ModeReglement') is-danger @enderror"
                    value="{{ old('ModeReglement', $facture->ModeReglement) }}"
                    placeholder="Ex : Virement, Chèque"
                    required>
                <span class="icon is-small is-left">
                    <i class="bi bi-credit-card-2-back-fill"></i>
                </span>
            </div>
            @error('ModeReglement')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Date d’entrée --}}
        <div class="field">
            <label for="DateEntree" class="label">Date d’entrée</label>
            <div class="control has-icons-left">
                <input
                    type="date"
                    id="DateEntree"
                    name="DateEntree"
                    class="input @error('DateEntree') is-danger @enderror"
                    value="{{ old('DateEntree', $facture->DateEntree) }}"
                    required>
                <span class="icon is-small is-left">
                    <i class="bi bi-calendar-fill"></i>
                </span>
            </div>
            @error('DateEntree')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Date d’échéance --}}
        <div class="field">
            <label for="DateEcheance" class="label">Date d’échéance</label>
            <div class="control has-icons-left">
                <input
                    type="date"
                    id="DateEcheance"
                    name="DateEcheance"
                    class="input @error('DateEcheance') is-danger @enderror"
                    value="{{ old('DateEcheance', $facture->DateEcheance) }}"
                    required>
                <span class="icon is-small is-left">
                    <i class="bi bi-calendar-check-fill"></i>
                </span>
            </div>
            @error('DateEcheance')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Date de remise --}}
        <div class="field">
            <label for="DateRemise" class="label">Date de remise (optionnelle)</label>
            <div class="control has-icons-left">
                <input
                    type="date"
                    id="DateRemise"
                    name="DateRemise"
                    class="input @error('DateRemise') is-danger @enderror"
                    value="{{ old('DateRemise', $facture->DateRemise) }}">
                <span class="icon is-small is-left">
                    <i class="bi bi-calendar-event-fill"></i>
                </span>
            </div>
            @error('DateRemise')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Date d’impayé --}}
        <div class="field">
            <label for="DateImpaye" class="label">Date d’impayé (optionnelle)</label>
            <div class="control has-icons-left">
                <input
                    type="date"
                    id="DateImpaye"
                    name="DateImpaye"
                    class="input @error('DateImpaye') is-danger @enderror"
                    value="{{ old('DateImpaye', $facture->DateImpaye) }}">
                <span class="icon is-small is-left">
                    <i class="bi bi-calendar-x-fill"></i>
                </span>
            </div>
            @error('DateImpaye')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Reference --}}
        <div class="field">
            <label for="Reference" class="label">Reference (optionnelle)</label>
            <div class="control has-icons-left">
                <input
                    type="text"
                    id="Reference"
                    name="Reference"
                    class="input @error('Reference') is-danger @enderror"
                    value="{{ old('Reference', $facture->Reference) }}"
                    placeholder="Reference">
                <span class="icon is-small is-left">
                    <i class="bi bi-bank2"></i>
                </span>
            </div>
            @error('Reference')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Libelle --}}
        <div class="field">
            <label for="Libelle" class="label">Libelle (optionnelle)</label>
            <div class="control has-icons-left">
                <input
                    type="text"
                    id="Libelle"
                    name="Libelle"
                    class="input @error('Libelle') is-danger @enderror"
                    value="{{ old('Libelle', $facture->Libelle) }}"
                    placeholder="Libelle">
                <span class="icon is-small is-left">
                    <i class="bi bi-bank2"></i>
                </span>
            </div>
            @error('Libelle')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Banque --}}
        <div class="field">
            <label for="Banque" class="label">Banque (optionnelle)</label>
            <div class="control has-icons-left">
                <input
                    type="text"
                    id="Banque"
                    name="Banque"
                    class="input @error('Banque') is-danger @enderror"
                    value="{{ old('Banque', $facture->Banque) }}"
                    placeholder="Nom de la Banque">
                <span class="icon is-small is-left">
                    <i class="bi bi-bank2"></i>
                </span>
            </div>
            @error('Banque')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Montant Total --}}
        <div class="field">
            <label for="MontantTotal" class="label">Montant total (€)</label>
            <div class="control has-icons-left">
                <input
                    type="number"
                    step="0.01"
                    id="MontantTotal"
                    name="MontantTotal"
                    class="input @error('MontantTotal') is-danger @enderror"
                    value="{{ old('MontantTotal', $facture->MontantTotal) }}"
                    placeholder="Ex : 1234.56"
                    required>
                <span class="icon is-small is-left">
                    <i class="bi bi-currency-euro"></i>
                </span>
            </div>
            @error('MontantTotal')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Statut --}}
        <div class="field">
            <label for="Statut" class="label">Statut</label>
            <div class="control has-icons-left">
                <div class="select @error('Statut') is-danger @enderror">
                    <select id="Statut" name="Statut" required>
                        <option value="">Sélectionnez</option>
                        <option value="Réglé" {{ old('Statut', $facture->Statut) == 'Réglé' ? 'selected' : '' }}>Réglé</option>
                        <option value="En attente" {{ old('Statut', $facture->Statut) == 'En attente' ? 'selected' : '' }}>En attente</option>
                        <option value="Impayé" {{ old('Statut', $facture->Statut) == 'Impayé' ? 'selected' : '' }}>Impayé</option>
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="bi bi-tag-fill"></i>
                </span>
            </div>
            @error('Statut')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div class="field">
            <label for="Description" class="label">Description (optionnelle)</label>
            <div class="control">
                <textarea
                    id="Description"
                    name="Description"
                    class="textarea @error('Description') is-danger @enderror"
                    rows="3"
                    placeholder="Informations supplémentaires...">{{ old('Description', $facture->Description) }}</textarea>
            </div>
            @error('Description')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Boutons --}}
        <div class="field is-grouped is-grouped-right mt-5">
            <p class="control">
                <button type="submit" class="button is-warning">
                    Mettre à jour
                </button>
            </p>
        </div>
    </form>
</section>

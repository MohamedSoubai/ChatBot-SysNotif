{{-- Fragment AJAX pour créer une facture --}}

<header class="modal-card-head">
    <p class="modal-card-title">
        <span class="icon-text">
            <span class="icon has-text-primary">
                <i class="bi bi-plus-square-fill"></i>
            </span>
            <span>Ajouter une facture</span>
        </span>
    </p>
</header>

<section class="modal-card-body">
    <form action="{{ route('factures.store') }}" method="POST" id="createFactureForm">
        @csrf

        {{-- Client (select) --}}
        <div class="field">
            <label for="CodeTiers" class="label">Client</label>
            <div class="control has-icons-left">
                <div class="select @error('codeC') is-danger @enderror">
                    <select id="CodeTiers" name="CodeTiers" required>
                        <option value="">Sélectionnez un client</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->CodeTiers }}" {{ old('CodeTiers') == $c->CodeTiers ? 'selected' : '' }}>
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

        {{-- Numéro Facture --}}
        <div class="field">
            <label for="NumeroFacture" class="label">Numéro de facture</label>
            <div class="control has-icons-left">
                <input
                    type="text"
                    id="NumeroFacture"
                    name="NumeroFacture"
                    class="input @error('NumeroFacture') is-danger @enderror"
                    value="{{ old('NumeroFacture') }}"
                    placeholder="Ex : F12345"
                    required>
                <span class="icon is-small is-left">
                    <i class="bi bi-hash"></i>
                </span>
            </div>
            @error('NumeroFacture')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
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
                    value="{{ old('Service') }}"
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
                    value="{{ old('ModeReglement') }}"
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

        {{-- Date d'entrée --}}
        <div class="field">
            <label for="DateEntree" class="label">Date d’entrée</label>
            <div class="control has-icons-left">
                <input
                    type="date"
                    id="DateEntree"
                    name="DateEntree"
                    class="input @error('DateEntree') is-danger @enderror"
                    value="{{ old('DateEntree', date('Y-m-d')) }}"
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
                    value="{{ old('DateEcheance') }}"
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
                    value="{{ old('DateRemise') }}">
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
                    value="{{ old('DateImpaye') }}">
                <span class="icon is-small is-left">
                    <i class="bi bi-calendar-x-fill"></i>
                </span>
            </div>
            @error('DateImpaye')
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
                    value="{{ old('Banque') }}"
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
                    value="{{ old('MontantTotal') }}"
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
                        <option value="Réglé" {{ old('Statut') == 'Réglé' ? 'selected' : '' }}>Réglé</option>
                        <option value="En attente" {{ old('Statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                        <option value="Impayé" {{ old('Statut') == 'Impayé' ? 'selected' : '' }}>Impayé</option>
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
                    placeholder="Informations supplémentaires...">{{ old('Description') }}</textarea>
            </div>
            @error('Description')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Boutons --}}
        <div class="field is-grouped is-grouped-right mt-5">
            <p class="control">
                <button type="button" class="button is-light" id="modalCloseCreateBtnFacture">
                    Annuler
                </button>
            </p>
            <p class="control">
                <button type="submit" class="button is-primary">
                    Enregistrer
                </button>
            </p>
        </div>
    </form>
</section>

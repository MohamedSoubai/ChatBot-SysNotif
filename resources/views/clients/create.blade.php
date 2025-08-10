{{-- Fragment AJAX chargé dans la modal Bulma (Créer un client) --}}

<header class="modal-card-head">
    <p class="modal-card-title">
        <span class="icon-text">
            <span class="icon has-text-primary">
                <i class="bi bi-person-plus-fill"></i>
            </span>
            <span>Créer un nouveau client</span>
        </span>
    </p>
</header>

<section class="modal-card-body">
    <form action="{{ route('clients.store') }}" method="POST" id="createClientForm">
        @csrf

        {{-- Code client --}}
        <div class="field">
            <label for="CodeTiers" class="label">Code client</label>
            <div class="control has-icons-left">
                <input
                    type="text"
                    id="CodeTiers"
                    name="CodeTiers"
                    class="input @error('CodeTiers') is-danger @enderror"
                    value="{{ old('CodeTiers') }}"
                    placeholder="Ex : C001"
                    required>
                <span class="icon is-small is-left">
                    <i class="bi bi-hash"></i>
                </span>
            </div>
            @error('CodeTiers')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Intitulé --}}
        <div class="field">
            <label for="Intitule" class="label">Intitulé</label>
            <div class="control has-icons-left">
                <input
                    type="text"
                    id="Intitule"
                    name="Intitule"
                    class="input @error('Intitule') is-danger @enderror"
                    value="{{ old('Intitule') }}"
                    placeholder="Nom ou raison sociale"
                    required>
                <span class="icon is-small is-left">
                    <i class="bi bi-building"></i>
                </span>
            </div>
            @error('Intitule')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Adresse --}}
        <div class="field">
            <label for="Adresse" class="label">Adresse</label>
            <div class="control has-icons-left">
                <textarea
                    id="Adresse"
                    name="Adresse"
                    class="textarea @error('Adresse') is-danger @enderror"
                    rows="3"
                    placeholder="Adresse complète">{{ old('Adresse') }}</textarea>
                <span class="icon is-small is-left">
                    <i class="bi bi-geo-alt-fill"></i>
                </span>
            </div>
            @error('Adresse')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Téléphone --}}
        <div class="field">
            <label for="Telephone" class="label">Téléphone</label>
            <div class="control has-icons-left">
                <input
                    type="text"
                    id="Telephone"
                    name="Telephone"
                    class="input @error('Telephone') is-danger @enderror"
                    value="{{ old('Telephone') }}"
                    placeholder="+212600000000"
                    required>
                <span class="icon is-small is-left">
                    <i class="bi bi-telephone-fill"></i>
                </span>
            </div>
            @error('Telephone')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="field">
            <label for="Email" class="label">Email</label>
            <div class="control has-icons-left">
                <input
                    type="email"
                    id="Email"
                    name="Email"
                    class="input @error('Email') is-danger @enderror"
                    value="{{ old('Email') }}"
                    placeholder="exemple@domaine.com"
                    required>
                <span class="icon is-small is-left">
                    <i class="bi bi-envelope-fill"></i>
                </span>
            </div>
            @error('Email')
                <p class="help is-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Boutons --}}
        <div class="field is-grouped is-grouped-right mt-5">
            <p class="control">
                <button type="submit" class="button is-primary">
                    Enregistrer
                </button>
            </p>
        </div>
    </form>
</section>

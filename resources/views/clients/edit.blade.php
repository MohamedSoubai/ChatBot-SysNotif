{{-- Fragment AJAX chargé dans la modal Bulma (Modifier un client) --}}

<header class="modal-card-head">
    <p class="modal-card-title">
        <span class="icon-text">
            <span class="icon has-text-warning">
                <i class="bi bi-pencil-square"></i>
            </span>
            <span>Modifier le client {{ $client->CodeTiers }}</span>
        </span>
    </p>
</header>

<section class="modal-card-body">
    <form action="{{ route('clients.update', $client->CodeTiers) }}" method="POST" id="editClientForm">
        @csrf
        @method('PUT')

        {{-- Code client (readonly) --}}
        <div class="field">
            <label for="CodeTiers" class="label">Code client</label>
            <div class="control">
                <input
                    type="text"
                    id="CodeTiers"
                    name="CodeTiers"
                    class="input is-static"
                    value="{{ $client->CodeTiers }}"
                    readonly>
            </div>
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
                    value="{{ old('Intitule', $client->Intitule) }}"
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
                    placeholder="Adresse complète">{{ old('Adresse', $client->Adresse) }}</textarea>
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
                    value="{{ old('Telephone', $client->Telephone) }}"
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
                    value="{{ old('Email', $client->Email) }}"
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
                <button type="submit" class="button is-warning">
                    Mettre à jour
                </button>
            </p>
        </div>
    </form>
</section>

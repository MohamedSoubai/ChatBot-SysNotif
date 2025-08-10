@component('mail::message')
# Bienvenue à bord, {{ $user->name }} 👋

Votre compte **Administrateur** a bien été créé.

Vous avez désormais accès à l'espace d'administration de la plateforme, où vous pouvez :

- 💼 Gérer les **factures** de vos clients  
- 👥 Ajouter, modifier ou supprimer des **clients**  
- 📊 Suivre l’évolution de l’activité en temps réel

@component('mail::button', ['url' => route('login')])
Accéder à mon espace
@endcomponent

Si vous n’êtes pas à l’origine de cette inscription, veuillez nous contacter immédiatement.

Merci pour votre confiance,  
**L’équipe de gestion des factures**
@endcomponent

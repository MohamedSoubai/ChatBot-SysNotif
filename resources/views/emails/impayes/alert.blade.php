@component('mail::message')
<style>
    .header {
        background: linear-gradient(135deg, #1a3a6c 0%, #2c5282 100%);
        padding: 25px 0;
        text-align: center;
        border-radius: 8px 8px 0 0;
        margin: -25px -25px 25px -25px;
    }
    .header h1 {
        color: white;
        font-size: 22px;
        margin: 0;
        letter-spacing: 0.5px;
    }
    .badge {
        display: inline-block;
        padding: 6px 12px;
        background-color: #e53e3e;
        color: white;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
        margin: 10px 0;
    }
    .divider {
        height: 1px;
        background: linear-gradient(90deg, #e2e8f0 0%, #cbd5e0 50%, #e2e8f0 100%);
        margin: 25px 0;
    }
    .footer {
        background-color: #f8fafc;
        padding: 20px;
        border-radius: 8px;
        margin-top: 30px;
        border: 1px solid #edf2f7;
    }
    .contact-item {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    .contact-icon {
        width: 24px;
        margin-right: 12px;
        text-align: center;
        color: #2d3748;
    }
</style>

<div class="header">
    <h1>RAPPEL DE FACTURES IMPAYÉES</h1>
</div>

Bonjour {{ $client->Intitule }},

<span class="badge">ATTENTION : {{ $factures->count() }} FACTURE(S) EN RETARD</span>

Veuillez trouver ci-dessous le détail des règlements en attente :

@component('mail::table')
| Référence       | Date d'échéance | Montant       |
| :-------------- | :-------------- | ------------: |
@foreach($factures as $f)
| **{{ $f->NumeroFacture }}** | {{ \Carbon\Carbon::parse($f->DateEcheance)->format('d/m/Y') }} | **{{ number_format($f->MontantTotal,2,',',' ') }} €** |
@endforeach
@endcomponent

<div style="text-align: right; margin-top: -20px;">
    <strong>Total dû : {{ number_format($factures->sum('MontantTotal'),2,',',' ') }} €</strong>
</div>

<div class="divider"></div>

**Merci de régulariser votre situation dès que possible**  
Vos options de paiement :
- Virement bancaire (coordonnées ci-dessous)
- Chèque à l'ordre de Sanitaire Ghandi
- Paiement en ligne via notre portail sécurisé

<div class="footer">
    <h3 style="margin-top: 0;">Coordonnées bancaires :</h3>
    <p>
        <strong>Sanitaire Ghandi</strong><br>
        IBAN : FR76 3000 4000 5000 6000 7000 800<br>
        BIC : ABCD12345
    </p>
    
    <h3>Contact :</h3>
    <div class="contact-item">
        <div class="contact-icon">📞</div>
        <div>01 23 45 67 89 (Service comptable)</div>
    </div>
    <div class="contact-item">
        <div class="contact-icon">✉️</div>
        <div>comptabilite@sanitaireghandi.fr</div>
    </div>
    <div class="contact-item">
        <div class="contact-icon">🌐</div>
        <div>www.sanitaireghandi.fr</div>
    </div>
</div>

@component('mail::button', ['url' => '#', 'color' => 'success'])
Accéder au portail de paiement
@endcomponent

Cordialement,  
**Service Financier**  
Sanitaire Ghandi  
*Votre partenaire sanitaire depuis 1985*
@endcomponent
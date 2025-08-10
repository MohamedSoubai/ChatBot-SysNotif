<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Clients;


class Factures extends Model
{
    use HasFactory;
    protected $table="FacturesTest";
    public $timestamps = false;
     protected $primaryKey = 'idFacture';
     public $incrementing = true;
     protected $keyType = 'int';
     protected $casts = [
        'dateEntree' => 'date',
        'dateEcheance' => 'date',
        'dateRemise' => 'date',
        'dateImpaye' => 'date',
    ];
    
 
     protected $fillable = [
         'CodeTiers',
         'NumeroFacture',
         'Service',
         'ModeReglement',
         'DateEntree',
         'DateEcheance',
         'DateRemise',
         'DateImpaye',
         'Reference',
         'Libelle',
         'Banque',
         'MontantTotal',
         'Statut',
         'Description',
     ];
 
     public function client()
     {
         return $this->belongsTo(Clients::class, 'codeTiers', 'CodeTiers');
     }
}

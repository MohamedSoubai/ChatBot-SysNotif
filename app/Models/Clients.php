<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Factures;


class Clients extends Model
{
    use HasFactory;
    protected $table="ClientsTest";
    public $timestamps = false;
     protected $primaryKey = 'CodeTiers';
     public $incrementing = false;
     protected $keyType = 'string';
 
     protected $fillable = [
         'CodeTiers',
         'Intitule',
         'Adresse',
         'Telephone',
         'Email',
     ];
 
     public function factures()
     {
         return $this->hasMany(Factures::class, 'codeTiers', 'CodeTiers');
     }
}

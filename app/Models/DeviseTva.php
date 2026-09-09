<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviseTva extends Model
{
    use HasFactory;
    protected $fillable = ['pays','devise','taxe','taux_tva','societe','societe_id','nom_user','user_id'];
}

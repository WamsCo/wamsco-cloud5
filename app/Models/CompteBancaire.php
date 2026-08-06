<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompteBancaire extends Model
{
    use HasFactory;
    protected $fillable = ['reference','nom_compte_bancaire','type_compte','solde','nom_banque','num_compte','nom_proprietaire','note','etat','societe','nom_user','user_id'];
}

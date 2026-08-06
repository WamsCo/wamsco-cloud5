<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaire extends Model
{
    use HasFactory;
    public $fillable = ['reference','libelle','entrepot','id_entrepot','date_inventaire','note','etat','user_id','nom_user','societe'];
}

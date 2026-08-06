<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste_travail extends Model
{
    use HasFactory;
    protected $fillable = ['nom_poste','description','societe','nom_user','user_id'];
}

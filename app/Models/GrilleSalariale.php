<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrilleSalariale extends Model
{
    use HasFactory;
    protected $fillable = ['reference','categorie','echelon','salaire_base','societe','nom_user','user_id'];
}

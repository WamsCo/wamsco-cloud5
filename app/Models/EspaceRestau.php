<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspaceRestau extends Model
{
    use HasFactory;
    protected $fillable = ['nom_espace','description','opacite','societe','nom_user','user_id'];
}

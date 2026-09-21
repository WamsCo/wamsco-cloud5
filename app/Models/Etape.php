<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    use HasFactory;
    protected $fillable = ['nom_etape','description','position','opacite','societe','societe_id','nom_user','user_id'];
}

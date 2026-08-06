<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapeTache extends Model
{
    use HasFactory;
    protected $fillable = ['nom_etape','description','opacite','societe','nom_user','user_id'];
}

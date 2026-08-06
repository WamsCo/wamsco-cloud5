<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    use HasFactory;
    protected $fillable = ['id_activite','page','type','subject', 'url', 'method', 'ip', 'agent', 'user_id','user_email','user_societe','profil'];
}

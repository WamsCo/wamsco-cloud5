<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    protected $fillable = ['title','societe','societe_id','nom_user','user_id','start', 'end',];
}

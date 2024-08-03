<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'prenom', 'cin', 'date_de_naissance', 'adresse', 'telephone',
        'diplome', 'level_study', 'email', 'experience', 'genre', 'school',
        'cv', 'lettre', 'status',
    ];
}

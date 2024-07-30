<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conge extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_de_conge_id', 'date_debut', 'date_fin', 'etat','user_id'
    ];

    public function typeDeConge()
    {
        return $this->belongsTo(TypeDeConge::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDeConge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function conges()
    {
        return $this->hasMany(Conge::class);
    }
}
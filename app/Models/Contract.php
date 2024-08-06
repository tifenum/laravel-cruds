<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'contract_type_id', 'start_date', 'end_date', 'contract_file'];

    public function typeDeConge()
    {
        return $this->belongsTo(TypeDeConge::class, 'type_de_conge_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

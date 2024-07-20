<?php

// Contract.php
// Contract.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'contract_type_id', 'start_date', 'end_date'];

    public function contractType()
    {
        return $this->belongsTo(ContractType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'department_type_id'];

    public function departmentType()
    {
        return $this->belongsTo(DepartmentType::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechStack extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function works()
    {
        return $this->belongsToMany(Work::class, 'work_tech_stacks');
    }
}

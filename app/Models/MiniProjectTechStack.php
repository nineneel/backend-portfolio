<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiniProjectTechStack extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;
}

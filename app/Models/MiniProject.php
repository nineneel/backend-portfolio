<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class MiniProject extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'project_name'
            ]
        ];
    }

    public function mini_project_tag()
    {
        return $this->belongsTo(MiniProjectTag::class);
    }

    public function tech_stacks()
    {
        return $this->belongsToMany(TechStack::class, 'mini_project_tech_stacks');
    }

    public function images()
    {
        return $this->hasMany(MiniProjectImage::class);
    }
}

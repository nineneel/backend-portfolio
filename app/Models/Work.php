<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Work extends Model
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

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function tech_stacks()
    {
        return $this->belongsToMany(TechStack::class, 'work_tech_stacks');
    }

    public function images()
    {
        return $this->hasMany(WorkImage::class);
    }
}

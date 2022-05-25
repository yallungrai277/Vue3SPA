<?php

namespace App\Models;

use App\Traits\PrimaryKeyUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, PrimaryKeyUuid;

    protected $fillable = [
        'name'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
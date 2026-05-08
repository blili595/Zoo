<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
class Enclosure extends Model
{
use HasFactory;

    protected $fillable = [
        'name',
        'limit',
        'feeding_at',
        'is_predator'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }

}

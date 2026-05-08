<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
class Animal extends Model
{
use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'species',
        'is_predator',
        'born_at',
        'picture',
        'enclosure_id'
    ];

    protected $dates = [
        'born_at' => 'datetime',
        'is_predator' => 'boolean'
    ];
    public function enclosure()
    {
        return $this->belongsTo(Enclosure::class);
    }
}

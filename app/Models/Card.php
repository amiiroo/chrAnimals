<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cards';

    protected $fillable = [
        'title', 
        'description', 
        'dop_info', 
        'kind', 
        'region', 
        'image',
        'status',
        'population',
        'habitat',
        'threats',
        'conservation'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    protected $dates = ['deleted_at'];
}
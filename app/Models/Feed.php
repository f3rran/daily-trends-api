<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feed extends Model
{
    protected $fillable = [
        'title',
        'content',
        'source',
        'created_at',
    ];

    protected $connection = 'mongodb';
    protected $collection = 'feeds';

    use HasFactory;
}

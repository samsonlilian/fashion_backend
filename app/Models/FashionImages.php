<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FashionImages extends Model
{
    use HasFactory;
    protected $fillable = ['fashion_id', 'image'];
}

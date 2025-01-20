<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlMapping extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'short_key'];
}

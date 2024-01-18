<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'about',
        'image1',
        'video_id',
        'descripton',
        'image_logo',
        'image2',
        'image3',

    ];
}

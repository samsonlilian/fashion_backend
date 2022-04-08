<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fashion extends Model
{
    use HasFactory;
    protected $fillable = [
        'gender', 'age_range', 'title', 'keywords', 'cover_img', 'desc', 'desc_long'
    ];

    public function getCoverImgAttribute($value)
    {
        return env('APP_URL') . '/storage/cover_image/' . $value;
    }

    public static function getFashions()
    {

        return Fashion::get();
    }
}

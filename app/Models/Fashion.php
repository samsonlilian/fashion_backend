<?php

namespace App\Models;

use App\Models\FashionImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fashion extends Model
{
    use HasFactory;
    protected $fillable = [
        'gender', 'age_range', 'title', 'keywords', 'cover_img', 'desc', 'desc_long', 'type_of_wears'
    ];

    protected $appends = ['is_ordered'];




    public function getCoverImgAttribute($value)
    {
        return env('APP_URL') . '/storage/cover_image/' . $value;
    }

    public static function getFashions($gender = 'all', $age_range = 'all', $type_of_wears = 'all', $keywords = 'all')
    {

        return  Fashion::orderBy('id', "DESC")
            ->when($gender != 'all', function ($q) use ($gender) {
                return $q->where('gender', $gender);
            })
            ->when($age_range != 'all', function ($q) use ($age_range) {
                return $q->where('age_range', (int) $age_range);
            })
            ->when($type_of_wears != 'all', function ($q) use ($type_of_wears) {
                return $q->where('type_of_wears', $type_of_wears);
            })
            ->when($keywords != 'all', function ($q) use ($keywords) {
                return $q->where('title', 'LIKE', "%{$keywords}%")->orWhere('keywords', 'LIKE', "%{$keywords}%");
            })
            ->take(16)
            ->get();
    }


    public function getIsOrderedAttribute()
    {
        $user = auth('sanctum')->user();
        $user_id = $user != null ? $user->id : 0;


        $order =  Order::where('fashion_id', $this->id)
            ->where('status', 0)
            ->where('user_id', $user_id)
            ->count();

        return $order > 0 ? true : false;
    }


    public function images()
    {
        return $this->hasMany(FashionImages::class);
    }
}

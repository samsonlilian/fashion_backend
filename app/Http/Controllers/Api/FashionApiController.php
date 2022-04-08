<?php

namespace App\Http\Controllers\Api;

use App\Models\Fashion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;

class FashionApiController extends Controller
{
    //
    public function searchFashion()
    {
        $gender = request('gender') ?? 'all';
        $age_range = request('age_range') ?? 'all';
        $type_of_wears = request('type_of_wears') ?? 'all';
        $keywords = request('title')   ?? 'all';





        $fashions =   Fashion::orderBy('id', "DESC")
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

        return response([
            'status' => 'success',
            'data' => $fashions
        ]);
    }


    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'gender' => 'required',
            'age_range' => 'required',
            'title' => 'required',
            'keywords' => 'required',
            'cover_img' => 'required',
            'desc' => 'required',
            'desc_long' => 'required',
            'type_of_wears' => 'required'
        ]);

        $new_fashion =  Fashion::create([
            'gender' => request('gender'),
            'age_range' => request('age_range'),
            'title' => request('title'),
            'keywords' => request('keywords'),
            'cover_img' => request('cover_img'),
            'desc' => request('desc'),
            'desc_long' => request('desc_long'),
            'type_of_wears' => request('type_of_wears'),
        ]);

        if ($request->hasFile('cover_img')) {
            $image_tmp = $request->cover_img;
            $name = $this->storeFile($image_tmp, 'cover_image');
            $new_fashion->cover_img = $name;
            $new_fashion->save();
        }
        return response([
            'status' => 'success',
            'data' => Fashion::getFashions()
        ]);
    }

    public function storeFile($file, $folderName)
    {

        $extension = $file->getClientOriginalExtension();
        $img = ImageManagerStatic::make($file)->encode($extension);
        $name = time() . '.' . $extension;

        Storage::disk('public')->put("$folderName/" . $name, $img);

        return $name;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Fashion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FashionImages;
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

        $fashions =  Fashion::getFashions($gender, $age_range, $type_of_wears, $keywords);

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
        //
        if ($request->hasFile('file_one')) {
            $image_tmp = $request->file_one;
            $name = $this->storeFile($image_tmp, 'images');
            FashionImages::create([
                'fashion_id' => $new_fashion->id,
                'image' => $name
            ]);
        }

        if ($request->hasFile('file_two')) {
            $image_tmp = $request->file_two;
            $name = $this->storeFile($image_tmp, 'images');
            FashionImages::create([
                'fashion_id' => $new_fashion->id,
                'image' => $name
            ]);
        }
        if ($request->hasFile('file_three')) {
            $image_tmp = $request->file_three;
            $name = $this->storeFile($image_tmp, 'images');
            FashionImages::create([
                'fashion_id' => $new_fashion->id,
                'image' => $name
            ]);
        }
        return response([
            'status' => 'success',
            'data' => Fashion::getFashions()
        ]);
    }


    //
    public function fashionDetail($id)
    {
        $fashionQuery = Fashion::with(['images'])->where('id', $id);
        if ($fashionQuery->exists()) {
            //
            return response([
                'status' => 'success',
                'data' => $fashionQuery->first()
            ]);
            //
        } else {
            return response([
                'status' => 'error',
                'data' => []
            ]);
        }
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

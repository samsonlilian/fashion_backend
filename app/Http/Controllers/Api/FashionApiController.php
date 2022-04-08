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
            'desc_long' => 'required'
        ]);

        $new_fashion =  Fashion::create([
            'gender' => request('gender'),
            'age_range' => request('age_range'),
            'title' => request('title'),
            'keywords' => request('keywords'),
            'cover_img' => request('cover_img'),
            'desc' => request('desc'),
            'desc_long' => request('desc_long'),
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

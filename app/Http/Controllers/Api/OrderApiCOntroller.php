<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Fashion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class OrderApiCOntroller extends Controller
{
    //

    public function store(Request $request)
    {
        //
        $user = auth('sanctum')->user();
        if ($user) {
            $this->validate($request, [
                'fashion_id' => 'required'
            ]);

            $check =  Order::where('status', 0)->where('user_id', $user->id)->where('fashion_id', $request->fashion_id)->count();

            if ($check == 0) {
                Order::create(
                    [
                        'fashion_id' => $request->fashion_id,
                        'user_id' => $user->id,
                        'no_of_items' => 1
                    ]
                );
                return response([
                    'status' => 'success',
                    'message' => 'order taken successfully',
                    'data' => Fashion::with(['images'])->where('id', $request->fashion_id)->first()
                ]);
            } else {
                return response([
                    'status' => 'success',
                    'message' => '',
                    'data' => Fashion::with(['images'])->where('id', $request->fashion_id)->first()
                ]);
            }
        }
        //
    }
}

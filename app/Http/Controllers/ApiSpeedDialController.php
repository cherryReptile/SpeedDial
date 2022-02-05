<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dial;
use Illuminate\Http\Request;

class ApiSpeedDialController extends Controller
{
    public function get($dial, Request $request)
    {
        $user = $request->user()->id;
        $dial = Dial::whereId($dial)->firstOrFail();
        $category = Category::whereId($dial->category_id)->firstOrFail();

        if ($category->user_id != $user) {
            return response([], 403);
        }

        return response([
            'category' => $category,
            'dial' => $dial
        ]);
    }

    public function getAll(Request $request)
    {
        $user = \Auth::user();
        $categories = Category::whereUserId($user->id)->get();
        $dials = \Auth::user()->dialThroughUser()->get();

        return response([
            'categories' => $categories,
            'dials' => $dials
        ]);
    }
}

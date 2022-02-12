<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Dial;
use Illuminate\Http\Request;

class SpeedDialController extends Controller
{
    public function get($category, Request $request)
    {
        $user = $request->user()->id;
        $category = Category::whereId($category)->firstOrFail();

        if ($category->user_id != $user) {
            return response([], 403);
        }

        $categoryWithRelation = new CategoryResource($category->load('dial'));

        return response($categoryWithRelation);
    }

    public function getAll(Request $request)
    {
        $user = $request->user()->id;
        $categories = Category::whereUserId($user);
        $speedDials = CategoryResource::collection($categories->with('dial')->get());

        return response($speedDials);
    }
}

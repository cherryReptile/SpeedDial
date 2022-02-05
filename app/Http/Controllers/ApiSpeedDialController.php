<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Dial;
use Illuminate\Http\Request;

class ApiSpeedDialController extends Controller
{
    public function get($category, Request $request)
    {
        $user = $request->user()->id;

        if (Category::whereId($category)->firstOrFail()->user_id != $user) {
            return response([], 403);
        }

        $categoryWithRelation = Category::with('dial')->findOrFail($category);

        $speedDial = new CategoryResource($categoryWithRelation);

        return response($speedDial);
    }

    public function getAll(Request $request)
    {
        $user = $request->user()->id;
        $categories = Category::whereUserId($user);
        $speedDials = CategoryResource::collection($categories->with('dial')->get());

        return response($speedDials);
    }
}

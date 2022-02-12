<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function add(Request $request)
    {
        $user = \Auth::user();
        $name = $request->validate([
            'name' => 'required|max:255'
        ]);

        $user->category()->create([
            'name' => $name['name']
        ]);

        $categoryId = Category::latest()->first()->id;

        return response([], 201)->withHeaders([
            'Location' => '/category/' . $categoryId
        ]);
    }

    public function get($category, Request $request)
    {
        $user = $request->user()->id;
        $category = Category::whereId($category)->firstOrFail();

        if ($user != $category->user_id) {
            return response([], 403);
        }

        return response(new CategoryResource($category));
    }

    public function getAll(Request $request)
    {
        $user = $request->user()->id;
        $categories = Category::whereUserId($user)->get();

        if (!$categories) {
            return response([], 403);
        }

        return response(CategoryResource::collection($categories));
    }

    public function edit($category, Request $request)
    {
        $user = $request->user()->id;
        $category = Category::whereId($category)->firstOrFail();

        if ($user != $category->user_id) {
            return response([], 403);
        }

        $category->update($request->all());

        return response(new CategoryResource($category));
    }

    public function delete($category, Request $request)
    {
        $user = $request->user()->id;
        $category = Category::whereId($category)->firstOrFail();

        if ($user != $category->user_id) {
            return response([], 403);
        }

        $category->delete();

        return response([], 204);
    }
}

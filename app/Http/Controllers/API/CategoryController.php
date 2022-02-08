<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class CategoryController extends Controller
{
    public function add(Request $request)
    {
        $user = \Auth::user();
        $name = $request->validate([
            'name' => 'required|max:255'
        ]);

        $user->category()->create([
            'name' => $name
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

        return response($category);
    }

    public function getAll(Request $request)
    {
        $user = $request->user()->id;
        $categories = Category::whereUserId($user)->get();

        if (!$categories) {
            return response([], 403);
        }

        return response($categories);
    }

    public function edit($category, Request $request)
    {
        $user = $request->user()->id;
        $category = Category::whereId($category)->firstOrFail();

        if ($user != $category->user_id) {
            return response([], 403);
        }

        $category->update($request->all());

        return response($category);
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

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class ApiCategoryController extends Controller
{
    public function add(Request $request)
    {
        $user = \Auth::user();
        $user->category()->create([
            'name' => $request->post('name')
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

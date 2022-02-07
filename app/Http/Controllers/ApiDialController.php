<?php

namespace App\Http\Controllers;

use App\Http\Resources\DialCollection;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Dial;
use App\Models\Category;
use DiDom\Document;
use DiDom\Query;
use phpDocumentor\Reflection\Types\Boolean;

class ApiDialController extends Controller
{
    public function add($category, Request $request)
    {
        $user = $request->user()->id;
        $category = Category::whereId($category)->firstOrFail();
        $url = $request->post('doc');

        if(filter_var($url, FILTER_VALIDATE_URL) === false){
            return response([], 400);
        }

        $document = new Document($url, true);
        $title = $document->first('title')->text();
        $description = (string)$document->first('meta[name=description]')->getAttribute('content');

        if ($user != $category->user_id) {
            return response([], 403);
        }

        $category->dial()->create([
            'title' => $title,
            'description' => $description,
            'active' => true
        ]);

        $dialId = Dial::latest()->first()->id;

        return response([], 201)
            ->withHeaders([
                'Location' => '/dial/' . $dialId
            ]);
    }

    public function get($dial, Request $request)
    {
        $user = $request->user()->id;
        $dial = Dial::whereId($dial)->firstOrFail();
        $category = Category::whereId($dial->category_id)->firstOrFail();

        if ($user != $category->user_id) {
            return response([], 403);
        }

        return response($dial);
    }

    public function getAll(Request $request)
    {
        $user = \Auth::user();
        $request->user();
        $dials = $user->dialThroughUser()->get();

        return response($dials);
    }

    public function edit($dial, Request $request)
    {
        $user = $request->user()->id;
        $dial = Dial::whereId($dial)->firstOrFail();
        $category = Category::whereId($dial->category_id)->firstOrFail();

        if ($category->user_id != $user) {
            return response([], 403);
        }

        $dial->update($request->all());

        return response($dial);
    }

    public function delete($dial, Request $request)
    {
        $user = $request->user()->id;
        $dial = Dial::whereId($dial)->firstOrFail();
        $category = Category::whereId($dial->category_id)->firstOrFail();

        if ($category->user_id != $user) {
            return response([], 403);
        }

        $dial->delete();

        return response([], 204);
    }
}

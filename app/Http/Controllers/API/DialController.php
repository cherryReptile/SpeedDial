<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DialCollection;
use App\Http\Resources\DialResource;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Dial;
use App\Models\Category;
use DiDom\Document;
use DiDom\Query;
use phpDocumentor\Reflection\Types\Boolean;

class DialController extends Controller
{
    public function add($category, Request $request)
    {
        $category = Category::whereId($category)->firstOrFail();

        if ($request->user()->cannot('view', $category)) {
            return response([], 403);
        }

        $url = $request->validate([
            'doc' => 'required|url'
        ]);
        $document = new Document($url['doc'], true);
        $title = $document->first('title')->text();
        $description = (string)$document->first('meta[name=description]')->getAttribute('content');
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
        $dial = Dial::whereId($dial)->firstOrFail();
        $category = Category::whereId($dial->category_id)->firstOrFail();

        if ($request->user()->cannot('view', $category)) {
            return response([], 403);
        }

        return response(new DialResource($dial));
    }

    public function getAll(Request $request)
    {
        $user = \Auth::user();
        $dials = $user->dialThroughUser()->get();

        return response(DialResource::collection($dials));
    }

    public function edit($dial, Request $request)
    {
        $dial = Dial::whereId($dial)->firstOrFail();
        $category = Category::whereId($dial->category_id)->firstOrFail();

        if ($request->user()->cannot('view', $category)) {
            return response([], 403);
        }

        $dial->update($request->all());

        return response(new DialResource($dial));
    }

    public function delete($dial, Request $request)
    {
        $dial = Dial::whereId($dial)->firstOrFail();
        $category = Category::whereId($dial->category_id)->firstOrFail();

        if ($request->user()->cannot('view', $category)) {
            return response([], 403);
        }

        $dial->delete();

        return response([], 204);
    }
}

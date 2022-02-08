<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dial;
use Illuminate\Http\Request;
use DiDom\Document;
use DiDom\Query;

class SpeedDialController extends Controller
{
    public function categoryCreate(Request $request)
    {
        $user = \Auth::user();

        if (!$request->post('name')) {
            return redirect()->back()->withErrors(['category' => 'Не передано название категории']);
        }

        $user->category()->create([
            'name' => $request->post('name')
        ]);

        return redirect()->back();
    }

    public function categoryDelete($category, Request $request)
    {
        $category = Category::whereId($category)->firstOrFail();
        $category->delete();

        return redirect()->back();
    }

    public function dialCreate($category, Request $request)
    {
        $category = Category::whereId($category)->firstOrFail();
//        $url = $request->validate([
//            'doc' => 'required|url'
//        ]);
        if (!$request->post('doc')) {
            return redirect()->back()->withErrors(['dial' => 'Не передан url']);
        }

        $document = new Document($request->post('doc'), true);
        $title = $document->first('title')->text();
        $description = (string)$document->first('meta[name=description]')->getAttribute('content');
        $category->dial()->create([
            'title' => $title,
            'description' => $description,
            'active' => true
        ]);

        return redirect()->back();
    }

    public function activity($dial, Request $request)
    {
        $dial = Dial::whereId($dial)->firstOrFail();
        $dial->update([
            'active' => !$dial->active,
        ]);

        return redirect()->back();
    }

    public function dialDelete($dial, Request $request)
    {
        $dial = Dial::whereId($dial)->firstOrFail();
        $dial->delete();

        return redirect()->back();
    }
}

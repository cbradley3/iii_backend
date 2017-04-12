<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use Response;

class ArticlesController extends Controller
{
  //list of articles
  public function index()
  {
    $articles = Article::all();

    return Response::json($articles);

  }

  //stores our articles
  public function store(Request $request)

  {
    $article = new Article;
    $article->title = $request->input('title');
    $article->body = $request->input('body');

    $image = $request->file('image');
    $imageName = $image->getClientOriginalName();
    $image->move("storage/", $imageName);
    $article->image = $request->root(). "/storage/".$imageName;

    $article->save();

    return Response::json(["success" => "You did it."]);

  }

  //updates out articles
  public function update($id, Request $request)

  {
    $article = Article::find($id);

    $article->title = $request->input('title');
    $article->body = $request->input('body');

    $image = $request->file('image');
    $imageName = $image->getClientOriginalName();
    $image->move("storage/", $imageName);
    $article->image = $request->root(). "/storage/".$imageName;



    $article->save();

    return Response::json(["success" => "Article Updated."]);
  }

  //shows a single article
  public function show($id)
  {
    $article = Article::find($id);

    return Response::json($article);
  }
  //deletes a single article
  public function destroy($id)

  {
    $article = Article::find($id);

    $article->delete();

    return Response::json(['success' => 'Deleted Article.']);
  }

}

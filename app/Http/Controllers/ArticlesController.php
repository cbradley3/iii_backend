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


  }

  //shows a single article
  public function show($id)
  {


  }
  //deletes a single article
  public function destroy($id)

  {


  }

}

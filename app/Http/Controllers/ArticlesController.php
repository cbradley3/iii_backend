<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use Response;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Carbon\Carbon;
use JWTAuth;

class ArticlesController extends Controller
{
  public function __construct()
  {
    $this->middleware("jwt.auth", ["only" => ["store","update", "destroy"]]);
  }
  //list of articles
  public function index()
  {
    $articles = Article::orderBy("id", "desc")->get();

    foreach($articles as $key => $article)
    {
      $articleDate = Carbon::createFromTimeStamp(strtotime($article->created_at))->diffForHumans();
      $article->articleDate = $articleDate;

      if(strlen($article->body) > 399)
      {
        $article->body = substr($article->body, 0, 399)."...";
      }
    }

    return Response::json($articles);

  }

  //stores our articles
  public function store(Request $request)

  {

    $rules = [
      'title' => 'required',
      'body' => 'required',
      'image' => 'required'

    ];

    $validator = Validator::make(Purifier::clean($request->all()), $rules);

    if($validator -> fails())
    {
      return Response::json(["error" => "You need to fill out all fields."]);
    }

    $article = new Article;
    $article->title = $request->input('title');
    $article->body = $request->input('body');

    $image = $request->file('image');
    $imageName = $image->getClientOriginalName();
    $image->move("storage/", $imageName);
    $article->image = $request->root(). "/storage/".$imageName;

    $article->save();

    return Response::json(["success" => "Success! You did it!"]);

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
    $article->articleDate = Carbon::createFromTimeStamp(strtotime($article->created_at))->diffForHumans();
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

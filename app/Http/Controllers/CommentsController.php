<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
      $rules=[
        "name" => "required",
        "body" => "required",
        "articleID" => "required",
      ];
      $validator = Validator::make(Purifier::clean($request->all()),$rules);

      if($validator->fails())
      {
        return Response::json(["error"=>"Please fill out all fields."]);
      }
      $comment = new Comment;
      $comment->userID = Auth::user()->id;
      $comment->articleID = $request->input("articleID");
      $comment->body = $request->input("body");
      $comment->save();

      return Response::json(["success"=>"Thanks for commenting!"]);

    }

    public function show($id)
    {
      $comments = Comment::where("articleID", "=",$id)->orderBy("id", "desc")->get();
      return Response::json($comments);

    }

    public function destroy($id);

    $comment = Comment::find($id);

    $comment->delete();

    return Response::json(['success' => 'Comments Deleted!']);
}

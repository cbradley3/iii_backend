<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Purifier;
use JWTAuth;
use Response;
use App\Article;
use App\Comment;

class CommentsController extends Controller
{
    public function __construct()
    {
      $this->middleware("jwt.auth", ["only" => ["store", "destroy"]]);
    }
    public function index($id)
    {
      $comments = Comment::where("comments.articleID","=",$id)
        ->join("users","comments.userID","=","users.id")
        ->orderBy("comments.id", "desc")
        ->select("comments.id","comments.body","comments.created_at","users.name")
        ->get();

        foreach($comments as $key => $comment)
        {
          $comment->commentDate = Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans();
        }
        return Response::json($comments);
    }

    public function store(Request $request)
    {
      $rules=[
        "body" => "required",
        "articleID" => "required",
      ];
      $validator = Validator::make(Purifier::clean($request->all()),$rules);

      if($validator->fails())
      {
        return Response::json(["error"=>"Please fill out all fields."]);
      }
      $check = Article::find($request->input("articleID"));

      if(empty($check))
      {
        return Response::json(["error"=>"Article does not exist"]);
      }
      $comment = new Comment;
      $comment->userID = Auth::user()->id;
      $comment->articleID = $request->input("articleID");
      $comment->body = $request->input("body");
      $comment->save();

      return Response::json(["success"=>"Thanks for commenting!"]);

    }



    public function destroy($id)
    {
      $comment = Comment::find($id);

      $comment->delete();

      return Response::json(['success' => 'Comments Deleted!']);
    }


}

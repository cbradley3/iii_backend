<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Response;
use Hash;
use App\User;
use JWTAuth;
use Auth;
use File;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware("jwt.auth", ["only" => ["getUser"]]);
  }

  public function index()
  {
    return File::get('index.html');
  }

  public function SignUp(Request $request)
  {
    $rules=[
      "username" => "required",
      "email" => "required",
      "password" => "required"
    ];
    $validator = Validator::make(Purifier::clean($request->all()),$rules);

    if($validator->fails())
    {
      return Response::json(["error"=>"Please fill out all fields."]);
    }

    $check = User::where("email","=",$request->input("email"))->orWhere("name","=",$request->input("username"))->first();

    if(!empty($check))
    {
      return Response::json(["error"=>"User already exists"]);
    }
    $user = new User;
    $user->name = $request->input("username");
    $user->email = $request->input("email");
    $user->password = Hash::make($request->input("password"));
    $user->roleID = 2;
    $user->save();

    return Response::json(["success"=>"Thanks for signing up!"]);
  }

  public function SignIn(Request $request)
  {
    $rules=[
      "email" => "required",
      "password" => "required",
    ];

    $validator = Validator::make(Purifier::clean($request->all()),$rules);

    if($validator->fails())
    {
      return Response::json(["error"=>"Please fill out all fields"]);
    }
    $email = $request->input("email");
    $password = $request->input("password");

    $cred = compact("email","password", ["email","password"]);
    $token = JWTAuth::attempt($cred);

    return Response::json(compact("token"));
  }

  public function getUser()
  {
    $user = Auth::user();
    $user = User::find($user->id);
    return Response::json($user);
  }
}

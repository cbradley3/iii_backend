<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Response;
use Hash;
use App\User;

class AuthController extends Controller
{
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
      return Response::json(["error"=>"Please fill out all fields"]);
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
    $user->save();

    return Response::json(["success"=>"Thanks for signing up!"]);
  }
}
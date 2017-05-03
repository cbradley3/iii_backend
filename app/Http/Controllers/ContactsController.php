<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use Response;
use Purifier;
use Mail;
use Illuminate\Support\Facades\Validator;

class ContactsController extends Controller
{
    public function index()
    {
      $contact = Contact::all();

      return Response::json($contact);
    }

    public function store(Request $request)
    {
      $rules=[
        'name' => 'required',
        'email' => 'required',
        'number' => 'required',
        'website' => 'required',
        'message' => 'required',
      ];

      $validator = Validator::make(Purifier::clean($request->all()), $rules);

      if($validator->fails())
      {
        return Response::json(["error" => "You need to fill out all fields."]);
      }

      $name = $request->input("name");
      $email = $request->input("email");
      $number = $request->input("number");
      $website = $request->input("website");
      $message = $request->input("message");

      Mail::send('emails.contact', array(
        'name' => $name,
        'email' => $email,
        'number' => $number,
        'website' => $website,
        'message' => $message
      ), function($name, $email, $number, $website, $message)
      {
        $message->to('cb.the.iii@gmail.com', 'Charlie Bradley')->subject('New Potential Client');
      });

      return Response::json(["success" => "You did it."]);
    }
    public function show($id)
    {
      $contact = Contact::find($id);

      return Response::json($contact);
    }

}

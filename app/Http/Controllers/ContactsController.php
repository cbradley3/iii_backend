<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use Response;

class ContactsController extends Controller
{
    public function index()
    {
      $contact = Contact::all();

      return Response::json($contact);
    }

    public function store(Request $request)
    {
      $contact = new Contact;
      $contact->name = $request->input('name');
      $contact->email = $request->input('email');
      $contact->number = $request->input('number');
      $contact->website = $request->input('website');
      $contact->message = $request->input('message');

      $contact->save();

      return Response::json(["success" => "You did it."]);
    }
    public function show($id)
    {
      $contact = Contact::find($id);

      return Response::json($contact);
    }

}

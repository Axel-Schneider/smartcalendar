<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\AssignOp\Concat;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        $waiting = Contact::all()->where('user_id', '=', Auth::id())->where('userRequest_id', '=', $request->user_id)->where('status', '=', 'waiting');
        $waitingFrom = Contact::all()->where('userRequest_id', '=', Auth::id())->where('user_id', '=', $request->user_id)->where('status', '=', 'waiting');
        if ($waiting->isEmpty() && $waitingFrom->isEmpty()) {
            $contact = new Contact();
            $contact->user_id = $request->user_id;
            $contact->userRequest_id = Auth::user()->id;
            $contact->status = 'waiting';
            $contact->save();
        }

        return redirect()->route('home');
    }
}

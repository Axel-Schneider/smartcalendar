<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Contact;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ContactRequest;
use PhpParser\Node\Expr\AssignOp\Concat;
use App\Notifications\ContactAddedNotification;

class ContactController extends Controller
{
    public function addContact(ContactRequest $request)
    {
        $waiting = Contact::all()->where('user_id', '=', Auth::id())->where('userRequest_id', '=', $request->user_id)->where('status', '=', 'waiting');
        $waitingFrom = Contact::all()->where('userRequest_id', '=', Auth::id())->where('user_id', '=', $request->user_id)->where('status', '=', 'waiting');
        if ($waiting->isEmpty() && $waitingFrom->isEmpty()) {
            $contact = new Contact();
            $contact->user_id = $request->user_id;
            $contact->userRequest_id = Auth::user()->id;
            $contact->status = 'waiting';
            $contact->save();
            User::all()->find($request->user_id)->notify(new ContactAddedNotification(Auth::user()));
            return response()->json(['success' => 'User added to your contact']);
        }else{
            return response()->json(['error' => 'User already in your contact'], 403);
        }

    }
}

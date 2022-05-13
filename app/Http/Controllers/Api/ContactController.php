<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ContactRequest;
use App\Http\Controllers\Controller;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\AssignOp\Concat;

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
            return response()->json(['success' => 'User added to your contact']);
        }else{
            return response()->json(['error' => 'User already in your contact'], 403);
        }

    }
}

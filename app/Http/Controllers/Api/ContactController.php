<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Contact;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\ContactRespondRequest;
use PhpParser\Node\Expr\AssignOp\Concat;
use App\Notifications\ContactAddedNotification;
use Illuminate\Notifications\Notification;

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

    public function respondContact(ContactRespondRequest $request){
        $contact = Contact::all()->where('user_id', '=', Auth::id())->where('userRequest_id', '=', $request->user_id);
        
        if ($contact->isEmpty()) {
            return response()->json(['error' => 'User is not in your contact request'], 403);
        } else{
            if($request->status == 'deny'){
                $contact->first()->delete();
                auth()->user()->notifications()->where('id', '=', $request->notification_id)->first()->markAsRead();
                return response()->json(['success' => 'Contact request denied']);
            }else if($contact->first()->status == 'waiting'){
                $contact->first()->status = $request->status;
                auth()->user()->notifications()->where('id', '=', $request->notification_id)->first()->markAsRead();
                if($request->status == 'block'){
                    $contact->first()->blocker_id = Auth::id();
                    $contact->first()->save();
                    return response()->json(['success' => 'Contact request blocked']);
                }
                $contact->first()->save();
                return response()->json(['success' => 'Contact request accepted']);
            }else{
                return response()->json(['error' => 'Contact request is already answered'], 403);
            }
        }
    }
}

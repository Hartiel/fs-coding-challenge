<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Channel;
use App\Models\Message;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::with('latestMessage')
            ->orderBy('name')
            ->get();

        $channels = Channel::all();

        $selectedContactId = $request->input('contact_id');
        $selectedContact = null;
        $messages = null;

        if ($selectedContactId) {
            $selectedContact = Contact::find($selectedContactId);
            
            $messages = Message::where('contact_id', $selectedContactId)
                ->orderBy('sent_at', 'desc')
                ->simplePaginate(30)
                ->withQueryString();
        }

        return Inertia::render('Chat', [
            'contacts' => $contacts,
            'channels' => $channels,
            'selectedContact' => $selectedContact,
            'messages' => $messages,
        ]);
    }
}

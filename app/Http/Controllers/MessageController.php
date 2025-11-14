<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Jobs\SendMessageJob;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(StoreMessageRequest $request)
    {
        $validated = $request->validated();

        $message = Message::create([
            'contact_id' => $validated['contact_id'],
            'channel_id' => $validated['channel_id'],
            'content' => $validated['content'],
            'direction' => 'out',
            'status' => 'sending',
            'sent_at' => now(),
        ]);

        SendMessageJob::dispatch($message);

        return redirect('/?contact_id=' . $validated['contact_id']);
    }

    public function checkStatus(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json([]);
        }

        $statuses = Message::whereIn('id', $ids)
            ->select('id', 'status')
            ->get();

        return response()->json($statuses);
    }
}

<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SupportController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();

        $tickets = SupportTicket::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get(['id', 'subject', 'message', 'status', 'resolution_note', 'created_at', 'updated_at']);

        return Inertia::render('creator/Support', [
            'tickets' => $tickets,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        SupportTicket::create([
            'user_id' => $user->id,
            'role' => $user->role ?? 'creator',
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);

        return redirect()->route('creator.support')->with('success', 'Ticket cree avec succes');
    }
}

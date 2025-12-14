<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SupportTicketsController extends Controller
{
    public function index(): Response
    {
        $tickets = SupportTicket::with('user:id,name,email,role')
            ->latest()
            ->get([
                'id',
                'user_id',
                'role',
                'subject',
                'message',
                'status',
                'resolution_note',
                'resolved_at',
                'created_at',
                'updated_at',
            ]);

        return Inertia::render('admin/Support', [
            'tickets' => $tickets,
            'statusOptions' => ['open', 'in_progress', 'resolved', 'closed'],
        ]);
    }

    public function update(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
            'resolution_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $ticket->status = $data['status'];
        $ticket->resolution_note = $data['resolution_note'] ?? null;
        $ticket->resolved_at = $data['status'] === 'resolved' ? now() : null;
        $ticket->save();

        return redirect()->route('admin.support.index')->with('success', 'Ticket mis à jour');
    }
}

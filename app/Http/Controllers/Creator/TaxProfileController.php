<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxProfileController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'mode' => ['required', Rule::in(['self', 'withheld'])],
            'country' => ['nullable', 'string', 'size:2'],
            'tax_id' => ['nullable', 'string', 'max:255'],
            'tax_rate_bps' => ['nullable', 'integer', 'min:0', 'max:10000'],
        ]);

        $user = $request->user();

        $user->taxProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'mode' => $data['mode'],
                'country' => $data['country'] ?? null,
                'tax_id' => $data['tax_id'] ?? null,
                'tax_rate_bps' => $data['tax_rate_bps'] ?? null,
            ],
        );

        return back()->with('success', 'Profil fiscal mis à jour.');
    }
}

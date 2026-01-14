<?php

namespace App\Http\Controllers\Client;

use App\Domain\Clients\Models\ClientPreference;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientPreferenceController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        $preference = $user->preference ?: ClientPreference::create([
            'user_id' => $user->id,
            'preferred_contact_method' => 'email',
            'marketing_opt_in' => false,
        ]);

        return view('client.preferences.edit', compact('preference'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'preferred_contact_method' => ['required', 'in:email,sms,letter'],
            'marketing_opt_in' => ['nullable'],
        ]);

        $preference = $user->preference ?: ClientPreference::create(['user_id' => $user->id]);

        $preference->update([
            'preferred_contact_method' => $validated['preferred_contact_method'],
            'marketing_opt_in' => $request->boolean('marketing_opt_in'),
        ]);

        return redirect()->route('client.preferences.edit')->with('success', 'Preferences saved.');
    }
}

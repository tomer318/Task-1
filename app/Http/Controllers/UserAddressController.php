<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward' => 'required|string|max:100',
            'address_detail' => 'required|string|max:255',
            'label' => 'nullable|string|max:50',
            'is_default' => 'nullable|boolean',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($request->has('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'city' => $validated['city'],
            'district' => $validated['district'],
            'ward' => $validated['ward'],
            'address_detail' => $validated['address_detail'],
            'label' => $validated['label'] ?? 'Nhà',
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('profile.edit')->with('status', 'address-created');
    }

    public function destroy(UserAddress $address)
    {
        if ($address->user_id === Auth::id()) {
            $address->delete();
        }

        return redirect()->route('profile.edit')->with('status', 'address-deleted');
    }
}
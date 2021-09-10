<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ExchangeResource;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        return $request->user();
    }

    public function update($user_id, Request $request)
    {
        $user = Auth::user();

        if ($user->id === intval($user_id)) {
            if ($request->input('update_password')) {
                $validated = $request->validate(
                    [
                        'current_password' => 'current_password',
                        'new_password' => 'required|confirmed|min:8|max:255',
                        'new_password_confirmation' => 'required|min:8|max:255|',
                    ]
                );

                if ($validated) {
                    $user->password = Hash::make($validated['new_password']);

                    $user->save();

                    return response()->json(['updated' => true], 200);
                }
            } else {
                $validated = $request->validate(
                    [
                        'name' => 'required|string|min:3|max:20',
                        'email' => 'required|email',
                        'phone_number' => 'required'
                    ]
                );

                if ($validated) {
                    $user->update(
                        [
                            'name' => $validated['name'],
                            'email' => $validated['email'],
                            'phone_number' => $validated['phone_number'],
                        ]
                    );


                    return response()->json(['user' => $user, 'updated' => true], 200);
                }
            }
        }

        return response()->json(['updated' => false], 500);
    }

    public function user_exchanges()
    {
        $user_exchanges = Auth::user()->exchanges;

        $user_exchanges = ExchangeResource::collection($user_exchanges);

        if ($user_exchanges) {
            return response()->json($user_exchanges);
        }
    }
}

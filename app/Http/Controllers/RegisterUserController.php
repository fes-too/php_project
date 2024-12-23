<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterUserController extends Controller
{
    public function register(){

        return view('auth.register');

    }

    public function store(Request $request){
        //validate request
        $request->validate([

            'name' => ['required', 'max:255', 'min:5', 'string'],
            'email' => 'required|email|unique:users',
            'password' => ['required', 'min:8', 'confirmed', Rules\Password::defaults()],

        ]);
        //create a new user
        $user = User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        //log user in
        Auth::login($user);
        //redirect the user
        return to_route('posts.index');

    }
}

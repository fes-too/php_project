<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginUserController extends Controller
{
    public function login(){
        return view('auth.login');
    }

    public function store(Request $request){
        //validate form data
        $request ->validate([
            'email' => 'required|email',
            'password' => 'required|min:8, string'
        ]);

        //Attempt to log the user in
        if (Auth::guard('web')->attempt(['email'=>$request->email,
        'password'=>$request->password])){
            //if successful, then redirect to their intended location
            return redirect()->intended(route('posts.index'));

        }else{
            //if unsuccessful, then redirect back to login with the form data
            return back()->withErrors([
                'email' => 'Invalid credentials',
            ]);
        }
    }

    public function logout(Request $request){

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('posts.index');
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Middleware\StartSession;

class AuthController extends Controller
{
    public function index(){
        if(session('username') == 'admin' && session('password')){
            return redirect()->route('home');
        }else{
            return view('login');
        }
        
    }
    public function authenticate(Request $request){
        $username = $request->input('username');
        $pass = $request->input('password');
        if($username == 'admin' && $pass == 'admin'){
            session(['username' => $username ,'password' => $pass]);
        }
    }

    public function logout(){
        session()->forget(['username','password']);
        return redirect()->route('login');
    }
}

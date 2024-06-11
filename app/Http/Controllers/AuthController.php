<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
//    Admin
    public function Login(LoginRequest $request)
    {
//        User::create([
//            'role' => 'admin',
//            'email' => 'admin@gmail.com',
//            'password' => 'admin'
//        ]);
        $credential = $request->validated();
        if(Auth::attempt($credential))
        {
            $request -> session() -> regenerate();
            $user=Auth::user();
            if($user['email']=='admin@gmail.com')
            {
                Session::put('nomSession','valeur');
                return redirect()->intended('admin/acceuil');
            }else{
                return  redirect()->intended('/')->with('error',"Vous n' êtes pas Authentifier")->onlyInput('email');
            }
        }else{
            return  redirect()->intended('/')->with('error',"Vous n' êtes pas Authentifier")->onlyInput('email');
        }
    }

//    Admin
    public function  logOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

//    Equipe Login

    public function LoginEquipe(Request $request)
    {
        $this->validate($request,[
            'email' =>'required',
            'password' => 'required',
        ]);

        if (Auth::guard('equipe')->attempt(['mail' => $request->email, 'password' => $request-> password])){

            return redirect('equipe/accueil');
        }

//        DB::table('equipe')->insert([
//            'nom' => 'Fandresena',
//            'mail' => 'jim@gmail.com',
//            'pwd' => Hash::make('jimmy'),
//        ]);
//        DB::table('equipe')->insert([
//            'nom' => 'Finiavana',
//            'mail' => 'mano@gmail.com',
//            'pwd' => Hash::make('mano'),
//        ]);
        return back()->withErrors([
            'email' => 'Veiller verifier votre mail',
            'password' => 'Veiller verifier  votre mot de passe',
        ]);
    }

    //log out Equipe

    public function logOutEquipe(Request $request)
    {
        Auth::guard('equipe')->logout();
        return redirect('equipe/login');
    }
}

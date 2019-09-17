<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class PassportController extends Controller
{
    
    public function register(Request $request)
    {
        // Validation des information de l'utilisateur
        $this->validate($request, [

            'name' => 'required|min:3',
            'email' => 'required|unique:users',
            'password' => 'required|min:4'
        ]);

        //  Enregisgtrement de l'utilisateur avant validation
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // Generation d'un token apres enregistrement
        $token = $user->createToken('MotDePasse')->accessToken;

        // Envoi de réponse à l'user
        return response()->json(['token' => $token]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users',
            'password' => 'required|min:4'
        ]);

        // Une fois que l'utilisateur a envoyé ses informations
        // On crée un tableau avec ses informations
        $credential = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(auth()->attempt($credential))
        {
            $token = auth()->user->createtoken('MotDePasse');
            return response()->json(['token' => $token]);
        }
        else{
            return response()->json(['errors' => 'Non autorisé']);
        }
    }


    public function details()
    {
        response()->json(['users'=>auth()->user()]);
    }
}

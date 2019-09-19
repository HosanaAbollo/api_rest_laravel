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
            'email' => 'required',  // Enlever le unique pour le debug via POSTMAN
            'password' => 'required|min:4'
        ]);

        /*
                TRAITEMENT DES MESSAGES D'ERREUR POUR LES CHAMPS NON REMPLIES OU NON EXACT
                // Ne pas oublier d'importer validator
                $validator = Validator::make($request->all(),[
                     'name' => 'required|min:3',
                    'email' => 'required',  // Enlever le unique pour le debug via POSTMAN
                    'password' => 'required|min:4'                  
                ]);

                if($validator->fails()){
                    // Si il y a une erreur on envoit les erreurs
                    return->response()->json([$validators->errors()])  //Mettre le status 422    data:
                } else  {}   // On créer l'user
        */

        //  Enregisgtrement de l'utilisateur avant validation
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // Generation d'un token apres enregistrement
            // AccessToken permet de generer un token de manière générale
        $token = $user->createToken('MotDePasse')->accessToken;

        // Envoi de réponse à l'user sous format JSON , ne pas oublier d'envoyer un status 
        // -> pour debug on peut envoyer $request a la place de $token et supprimer $user et $token temporairement
        // Avec Postman ne pas oublier d'aciter le x-www-form-urlencoded
        return response()->json(['token' => $token]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
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
            $token = auth()->user->createToken('MotDePasse')->accessToken;
            
            // Envoi de réponse à l'user sous format JSON , ne pas oublier d'envoyer un status
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

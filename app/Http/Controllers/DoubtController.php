<?php

namespace App\Http\Controllers;

use App\Models\Doubt;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class DoubtController extends Controller
{
       // =========================================================================
       public function index(){

        $doubts = Doubt::orderBy('created_at', 'desc')->get();

        foreach($doubts as $doubt){
            $respuestas = $doubt->respuestas;
            $user= User::find($doubt->user_id);
            $doubt->user_nombre = $user->nombre. ' '. $user->apellidos;
            $user->trabajo;
            foreach($respuestas as $respuesta){
                $user = $respuesta->user;
                if(isset($user->password)){
                    $user->password = 'private';
                }
                
            }
        }

        return response()->json($doubts,200);
    }
    // =========================================================================
    public function show($id){

        $doubt = Doubt::find($id);

        if(!isset($inc)){

            throw new Exception('doubt does not exist in database');

        }else{
            

            return response()->json($doubt,200);
        }
    }
    // =========================================================================
    public function getRelated($id){
        try {
            $doubts = Doubt::where('user_id', $id)->orderBy('created_at', 'desc')->get();
            foreach($doubts as $doubt){
                $respuestas = $doubt->respuestas;
                $doubt->user;
                foreach($respuestas as $respuesta){
                    $user = $respuesta->user;
                    if(isset($user->password)){
                        $user->password = 'private';
                    }
                    
                }
            }
           
            return response()->json($doubts,200);

        } catch (Exception $exception) {
            throw new Exception('Error getting related doubts of the user: '.$exception->getMessage());
        }
    }   
   
    

    // =========================================================================
    public function store(Request $request){
       
        $body = $request->getContent();
        $doubt = json_decode($body);

        $doubtNueva = new Doubt;

        try {

            $doubtNueva->user_id = $doubt->user_id;
            $doubtNueva->titulo = $doubt->titulo;
            $doubtNueva->descripcion = $doubt->descripcion;
            $doubtNueva->foto = $doubt->foto;
            $doubtNueva->estado = $doubt->estado;
            
            $doubtNueva->save();

         

            return response()->json(['message'=>'Doubt Sended!'],200);

        } catch (Exception $exception) {

            throw new Exception('Error saving data, Issue can not be stored'.$exception->getMessage());

        }
       
    }
    // =============================================================================
    public function delete($id){
        try {

          
        } catch (Exception $exception) {

            
        }
       
    }
    // =========================================================================
    public function actualizar(Request $request){
        try {
            $body = $request->getContent();
            $doubtFrontend = json_decode($body);

            $doubtBackend = Doubt::find($doubtFrontend->id);

            $doubtBackend->user_id = $doubtFrontend->user_id;
            $doubtBackend->titulo = $doubtFrontend->titulo;
            $doubtBackend->descripcion = $doubtFrontend->descripcion;
            $doubtBackend->foto = $doubtFrontend->foto;
            $doubtBackend->estado = $doubtFrontend->estado;          

            $doubtBackend->update();

        } catch (Exception $exception) {

            throw new Exception('Error updating issue data'.$exception->getMessage());
        }
    }
   
   
}

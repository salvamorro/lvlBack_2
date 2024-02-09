<?php

namespace App\Http\Controllers;

use App\Models\Doubt;
use App\Models\User;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoubtController extends Controller
{
       // =========================================================================
    public function index(){

        $doubts = Doubt::orderBy('created_at', 'desc')->get();

        foreach($doubts as $doubt){
            $respuestas = $doubt->respuestas;
            $user= User::find($doubt->user_id);
            if(isset($user)){
                $doubt->user_nombre = $user->nombre. ' '. $user->apellidos;
                $user->trabajo;    
            }
            foreach($respuestas as $respuesta){
                $user = $respuesta->user;
                
            }
        }

        return response()->json($doubts,200);
    }

    public function pending(){
        $doubts = Doubt::where('estado','!=','Solved')->orderBy('created_at', 'desc')->get();

        foreach($doubts as $doubt){
            $respuestas = $doubt->respuestas;
            $user= User::find($doubt->user_id);
            if(isset($user)){
                $doubt->user_nombre = $user->nombre. ' '. $user->apellidos;
                $user->trabajo;    
            }
            foreach($respuestas as $respuesta){
                $user = $respuesta->user;
                
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
       
      
        $doubtNueva = new Doubt;

        try {

            $doubtNueva->user_id = $request->user_id;
            $doubtNueva->titulo = $request->titulo;
            $doubtNueva->descripcion = $request->descripcion;
            $doubtNueva->foto = $request->foto;
            $doubtNueva->estado = $request->estado;
            $doubtNueva->archivo = '';
            $doubtNueva->save();

            if($request->hasFile('archivo') && $request->file('archivo')->isValid()){
                $nombreArchivo = $request->file('archivo')->hashName();
                $path = 'docs/doubts/'.$doubtNueva->id.'/'.$nombreArchivo;
                Storage::disk('public')->put($path, file_get_contents($request->file('archivo')));
                $doubtNueva->update(['archivo'=>"/storage/".$path]);
            }
            
            

         

            return response()->json(['message'=>'Doubt Sended!'],200);

        } catch (Exception $exception) {

            throw new Exception('Error saving data, Issue can not be stored'.$exception->getMessage());

        }
       
    }
    // =============================================================================
    public function delete($id){
        
        try {
            $doubt = Doubt::find($id);
            if(!isset($doubt)){
                return response()->json(['message'=>'Doubt Not Found!'],404);
            }
            $doubt->delete();

            $borrado = Storage::disk('public')->deleteDirectory('docs/'.$id);
            $mensaje = 'Doubt Deleted!';
            if($borrado){
                $mensaje += ' And it´s documents';
            }
            return response()->json(['message'=>$mensaje],200);
            
        } catch (Exception $exception) {
            throw new Error('Error deleting doubt data'.$exception->getMessage());
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

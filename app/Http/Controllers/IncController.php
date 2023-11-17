<?php

namespace App\Http\Controllers;

use App\Models\Inc;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class IncController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     // =========================================================================
     public function index(){

        $incs = Inc::orderBy('created_at', 'desc')->get();

        foreach ($incs as $inc) {
            $inc->piso;
            $inc->puerta;
            $user = $inc->user;
            $user->trabajo;
            
            $respuestas = $inc->respuestas;
            foreach( $respuestas as $respuesta) {
                $respuesta->user;
            }
        }
    

        return response()->json($incs,200);
    }
    // =========================================================================
    public function show($id){

        $inc = Inc::find($id);

        if(!isset($inc)){

            throw new Exception('Issue does not exists in database');

        }else{
            $inc->user;
            $inc->piso;
            $inc->puerta;
            $inc->respuestas;

            return response()->json($inc,200);
        }
    }
    // =========================================================================
    public function getRelated($id){
        try {
            $incs = Inc::where('user_id', $id)->orderBy('created_at', 'desc')->get();

            foreach ($incs as $inc) {
                $inc->piso;
                $inc->puerta;
                $inc->user;
                $respuestas = $inc->respuestas;
                foreach( $respuestas as $respuesta) {
                    $respuesta->user;
                }
            }
            return response()->json($incs,200);

        } catch (Exception $exception) {
            throw new Exception('Error getting related Issues of the user: '.$exception->getMessage());
        }
    }   

    // =========================================================================
    public function store(Request $request){
       
        $body = $request->getContent();
        $inc = json_decode($body);

        $incNueva = new Inc;

        try {

            $incNueva->user_id = $inc->user_id;
            $incNueva->puerta_id =  $inc->puerta_id;
            $incNueva->nombre = $inc->nombre;
            $incNueva->descripcion = $inc->descripcion;
            $incNueva->estado = $inc->estado;
            $incNueva->piso_id = $inc->piso_id;
            $incNueva->foto = $inc->foto;
            $incNueva->closed = $inc->closed;
           
            
            $incNueva->save();

        
            return response()->json(['message'=>'Issue Sended!'],200);

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
    public function actualizar(Request $request, $id){
        try {
            $body = $request->getContent();
            $incFrontend = json_decode($body);

            $incBackend = Inc::find($id);

            $incBackend->user_id = $incFrontend->user_id;
            $incBackend->puerta_id =  $incFrontend->puerta_id;
            $incBackend->nombre = $incFrontend->nombre;
            $incBackend->descripcion = $incFrontend->descripcion;
            $incBackend->estado = $incFrontend->estado;
            $incBackend->piso_id = $incFrontend->piso_id;
            $incBackend->foto = $incFrontend->foto;
            $incBackend->closed = $incFrontend->closed;
          

            $incBackend->update();

        } catch (Exception $exception) {

            throw new Exception('Error updating issue data'.$exception->getMessage());
        }
    }

   
    
}

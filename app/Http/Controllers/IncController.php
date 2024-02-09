<?php

namespace App\Http\Controllers;

use App\Mail\AvisoIncidenciaAbierta;
use App\Models\Inc;
use App\Models\Piso;
use App\Models\Puerta;
use App\Models\Trabajo;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class IncController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     // =========================================================================
     public function indexAbiertas(){

        $incs = Inc::where('estado','!=', 'Closed')->orderBy('created_at', 'desc')->get();
        foreach ($incs as $inc) {
            $user = User::find($inc->user_id);
            if(isset($user)){
                $inc->user_nombre = $user->nombre .' '.$user->apellidos;
            }else{
                $inc->user_nombre = '';
            } 
            $piso = Piso::find($inc->piso_id);
            if(isset($piso)){
                $inc->piso_nombre = $piso->nombre;
                $inc->venue_id = $piso->venue_id;
            }else{
                $inc->piso_nombre = '';
            } 
            $puerta = Puerta::find($inc->puerta_id);
            if(isset($puerta)){
                $inc->puerta_nombre = $puerta->nombre;
            }else{
                $inc->puerta_nombre = '';
            } 
           

        }


        
    

        return response()->json($incs,200);
    }
    public function indexTodas(){

        $incs = Inc::orderBy('created_at', 'desc')->get();
        foreach ($incs as $inc) {
            $user = User::find($inc->user_id);
            if(isset($user)){
                $inc->user_nombre = $user->nombre .' '.$user->apellidos;
            }else{
                $inc->user_nombre = '';
            } 
            $piso = Piso::find($inc->piso_id);
            if(isset($piso)){
                $inc->piso_nombre = $piso->nombre;
                $inc->venue_id = $piso->venue_id;
            }else{
                $inc->piso_nombre = '';
            } 
            $puerta = Puerta::find($inc->puerta_id);
            if(isset($puerta)){
                $inc->puerta_nombre = $puerta->nombre;
            }else{
                $inc->puerta_nombre = '';
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
            $user = User::find($inc->user_id);
            $inc->user_nombre = $user->nombre." ".$user->apellidos;
            $trabajo = Trabajo::find($user->trabajo_id);
            $inc->trabajo_dept = $trabajo->departamento;
            // $inc->user;
            // $inc->piso;
            // $inc->puerta;
            $inc->respuestas;

            return response()->json($inc,200);
        }
    }
    // =========================================================================
    public function getRelated($id){
        try {
            $incs = Inc::where('user_id', $id)->orderBy('created_at', 'desc')->get();

            foreach ($incs as $inc) {
              
                $user = User::find($inc->user_id);
                $inc->user_nombre = $user->nombre . ' ' . $user->apellidos;
                $respuestas = $inc->respuestas;
                // foreach( $respuestas as $respuesta) {
                //     $respuesta->user;
                // }
            }
            return response()->json($incs,200);

        } catch (Exception $exception) {
            throw new Exception('Error getting related Issues of the user: '.$exception->getMessage());
        }
    }   
    public function getWithVenue($venue_id){
        try {
            $incs = Inc::all() ;
            foreach($incs as $inc){

            }
            return response()->json($incs,200);

        } catch (Exception $exception) {
            throw new Exception('Error getting related  Issues of the VENUE : '.$exception->getMessage());
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
    public function getResponsable($trabajo){
        $responsables = User::where('departamento',$trabajo->departamento)->where('admin',1)->get();
 
        return $responsables;
 
     }

   
    
}

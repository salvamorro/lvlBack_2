<?php

namespace App\Http\Controllers;

use App\Models\Rrhh;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class RrhhController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     // =========================================================================
     public function index(){

        $rrhhs = Rrhh::orderBy('created_at', 'desc')->get();

        foreach($rrhhs as $rrhh){
            
            $user = User::find($rrhh->user_id);
            $rrhh->user_nombre = $user->nombre . ' ' . $user->apellidos;
            $user->trabajo;
            $respuestas = $rrhh->respuestasRRHH;
            foreach($respuestas as $respuesta){
                $respuesta->user;
            }
        }
        return response()->json($rrhhs,200);
    }
    // =========================================================================
    public function show($id){

        

        try {
            $rrhh = Rrhh::find($id);

            if(!isset($rrhh)){
    
                throw new Exception('Leave request does not exists in database');
    
            }else{
    
                return response()->json($rrhh,200);
            }

        } catch (Exception $exception) {
            throw new Exception('Error getting Leave Request of the user: '.$exception->getMessage());
        }
    }
    // =========================================================================
    public function getRelated($id){
        try {
            $rrhhs = Rrhh::where('user_id', $id)->orderBy('created_at', 'desc')->get();

            foreach($rrhhs as $rrhh){
                $user = $rrhh->user;
                $user->trabajo;
                $respuestas = $rrhh->respuestasRRHH;
                foreach($respuestas as $respuesta){
                    $respuesta->user;
                }
            }
            
            return response()->json($rrhhs,200);

        } catch (Exception $exception) {
            throw new Exception('Error getting related Leave Request of the user: '.$exception->getMessage());
        }
    }   

    // =========================================================================
    public function store(Request $request){
       
        $body = $request->getContent();
        $rrhh = json_decode($body);

        $rrhhNueva = new Rrhh;

        try {

            $rrhhNueva->user_id = $rrhh->user_id;
            $rrhhNueva->start =  Carbon::parse($rrhh->start);
            $rrhhNueva->end = Carbon::parse($rrhh->end);
            $rrhhNueva->descripcion = $rrhh->descripcion;
            $rrhhNueva->estado = $rrhh->estado;
           
            
            $rrhhNueva->save();

        
            return response()->json(['message'=>'Leave Request Sended!'],200);

        } catch (Exception $exception) {

            throw new Exception('Error saving data, Leave request could not be stored'.$exception->getMessage());

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
            $rrhhFrontend = json_decode($body);

            $rrhhBackend = Rrhh::find($rrhhFrontend->id);

            $rrhhBackend->user_id = $rrhhFrontend->user_id;
            $rrhhBackend->start = Carbon::parse($rrhhFrontend->start) ;
            $rrhhBackend->end = Carbon::parse($rrhhFrontend->end);
            $rrhhBackend->descripcion = $rrhhFrontend->descripcion;
            $rrhhBackend->estado = $rrhhFrontend->estado;
          

            $rrhhBackend->update();

        } catch (Exception $exception) {

            throw new Exception('Error updating Leave Request data'.$exception->getMessage());
        }
    }

   
    
}

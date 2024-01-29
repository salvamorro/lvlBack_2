<?php

namespace App\Http\Controllers;

use App\Mail\AvisoIncidenciaAbierta;
use App\Models\Inc;
use App\Models\Respuesta;
use App\Models\User;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RespuestaController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // =========================================================================
    public function index(){

        $respuestas = Respuesta::orderbyDesc('created_at','desc')->get();

        return response()->json($respuestas,200);
    }
    // =========================================================================
    public function show($id){
        $respuesta = Respuesta::find($id);
        if(!isset($respuesta)){
            throw new Error('Could not find the Answer in database',400);
        }
            
        return response()->json($respuesta,200);
        
    }
    // =========================================================================
    public function store(Request $request){
       
        try {
            $respuestaNueva = new Respuesta;
            
            $body = $request->getContent();
            $respuesta = json_decode($body);
            
            $respuestaNueva->leida = $respuesta->leida;
            $respuestaNueva->user_id = $respuesta->user_id;
            $respuestaNueva->mensaje = $respuesta->mensaje;
            $respuestaNueva->tipo = $respuesta ->tipo;
           
            $respuestaNueva->inc_id = $respuesta->inc_id;
            $respuestaNueva->foto = $respuesta->foto;

            $respuestaNueva->save();



            return response()->json(['message'=>'Answer Added!'],200);

        } catch (Exception $exception) {

            throw new Error('Could not save the answer in the database: '.$exception->getMessage());
        }
       
    }
     // =========================================================================
     public function actualizar(Request $request, $id){
        try {
          
            $body = $request->getContent();
            $data = json_decode($body);

            $respuestaActualizar = Respuesta::find($id);
            if(!isset($respuestaActualizar)){
                throw new Error('Could not find the answer in database',400);
            }
            $respuestaActualizar->leida = $data->leida;
            $respuestaActualizar->mensaje = $data->mensaje;
            $respuestaActualizar->tipo = $data ->tipo;
          
            $respuestaActualizar->foto = $data->foto;
            $respuestaActualizar->update();    

            return response()->json(['message'=> 'Answer Updated'],200);

        } catch (Exception $exception) {

            throw new Error('Could not update the answer in the database'.$exception->getMessage());
        }
    }
    // =============================================================================
    public function delete($id){
       
            try {
                $respuesta = Respuesta::find($id);
    
                if(!isset($respuesta)){
                    throw new Error('Could not find the answer in database',400);
    
                }
                $respuesta->delete();
    
                return response()->json(["message"=> "Answer Deleted"]);
                
    
            } catch (Exception $exception) {
    
                throw new Error('Could not delete the answer in the database'.$exception->getMessage());
            }
        
        
       
    }

   
   

   

   
}


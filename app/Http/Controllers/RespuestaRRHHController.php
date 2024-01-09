<?php

namespace App\Http\Controllers;

use App\Models\RespuestaRRHH;
use Error;
use Exception;
use Illuminate\Http\Request;

class RespuestaRRHHController extends Controller
{
     // =========================================================================
     public function index(){

        $respuestas = RespuestaRRHH::orderby('created_at','desc')->get();

        return response()->json($respuestas,200);
    }
    // =========================================================================
    public function show($id){
        $respuesta = RespuestaRRHH::find($id);
        if(!isset($respuesta)){
            throw new Error('Could not find the Answer HHRR in database',400);
        }
            
        return response()->json($respuesta,200);
        
    }
    // =========================================================================
    public function store(Request $request){
       
        try {
            $respuestaNueva = new RespuestaRRHH;
            
            $body = $request->getContent();
            $respuesta = json_decode($body);
            
            $respuestaNueva->leida = $respuesta->leida;
            $respuestaNueva->user_id = $respuesta->user_id;
            $respuestaNueva->mensaje = $respuesta->mensaje;
            $respuestaNueva->tipo = $respuesta ->tipo;
           
            $respuestaNueva->rrhh_id = $respuesta->rrhh_id;

            $respuestaNueva->save();

            return response()->json(['message'=>'Answer Added!'],200);

        } catch (Exception $exception) {

            throw new Error('Could not save the answer HHRR in the database'.$exception->getMessage());
        }
       
    }
     // =========================================================================
     public function actualizar(Request $request){
        try {
          
            $body = $request->getContent();
            $data = json_decode($body);

            $respuestaActualizar = RespuestaRRHH::find($data->id);
            if(!isset($respuestaActualizar)){
                throw new Error('Could not find the answer HHRR in database',400);
            }
            $respuestaActualizar->leida = $data->leida;
            $respuestaActualizar->mensaje = $data->mensaje;
            $respuestaActualizar->tipo = $data ->tipo;
          
            $respuestaActualizar->update();    

            return response()->json(['message'=> 'Answer Updated'],200);

        } catch (Exception $exception) {

            throw new Error('Could not update the answer HHRR in the database'.$exception->getMessage());
        }
    }
    // =============================================================================
    public function delete($id){
       
            try {
                $respuesta = RespuestaRRHH::find($id);
    
                if(!isset($respuesta)){
                    throw new Error('Could not find the answer HHRR in database',400);
    
                }
                $respuesta->delete();
    
                return response()->json(["message"=> "Answer Deleted"]);
                
    
            } catch (Exception $exception) {
    
                throw new Error('Could not delete the answerHHRR in the database'.$exception->getMessage());
            }
        
        
       
    }

   
   

   

   
}

<?php

namespace App\Http\Controllers;

use App\Models\RespuestaDoubt;
use Error;
use Exception;
use Illuminate\Http\Request;

class RespuestaDoubtController extends Controller{
// =========================================================================
public function index(){

    $respuestas = RespuestaDoubt::orderby('created_at','desc')->get();

    foreach($respuestas as $respuesta){
        $user = $respuesta->user;
        if(isset($user->password)){
            $user->password = 'private';
        }
        
    }

    return response()->json($respuestas,200);
}
// =========================================================================
public function show($id){
    $respuesta = RespuestaDoubt::find($id);
    if(!isset($respuesta)){
        throw new Error('Could not find the Answer Doubt in database',400);
    }
        
    return response()->json($respuesta,200);
    
}
// =========================================================================
public function store(Request $request){
   
    try {
        $respuestaNueva = new RespuestaDoubt;
        
        $body = $request->getContent();
        $respuesta = json_decode($body);
        
        $respuestaNueva->user_id = $respuesta->user_id;
        $respuestaNueva->mensaje = $respuesta->mensaje;
        $respuestaNueva->tipo = $respuesta ->tipo;
        $respuestaNueva->doubt_id = $respuesta->doubt_id;
        $respuestaNueva->foto = $respuesta->foto;
        

        $respuestaNueva->save();

        return response()->json(['message'=>'Answer Added!'],200);

    } catch (Exception $exception) {

        throw new Error('Could not save the answer doubt in the database'.$exception->getMessage());
    }
   
}
 // =========================================================================
 public function actualizar(Request $request){
    try {
      
        $body = $request->getContent();
        $data = json_decode($body);

        $respuestaActualizar = RespuestaDoubt::find($data->id);
        if(!isset($respuestaActualizar)){
            throw new Error('Could not find the answer DOUBT in database',400);
        }
        $respuestaActualizar->mensaje = $data->mensaje;
        $respuestaActualizar->tipo = $data ->tipo;
      
        $respuestaActualizar->update();    

        return response()->json(['message'=> 'Answer Updated'],200);

    } catch (Exception $exception) {

        throw new Error('Could not update the answer Doubt in the database'.$exception->getMessage());
    }
}
// =============================================================================
public function delete($id){
   
        try {
            $respuesta = RespuestaDoubt::find($id);

            if(!isset($respuesta)){
                throw new Error('Could not find the answer Doubt in database',400);

            }
            $respuesta->delete();

            return response()->json(["message"=> "Answer Deleted"]);
            

        } catch (Exception $exception) {

            throw new Error('Could not delete the answer Doubt in the database'.$exception->getMessage());
        }
    
    
   
}







}


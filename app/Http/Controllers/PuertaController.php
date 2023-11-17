<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use App\Models\Puerta;
use App\Models\Trabajo;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class PuertaController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // =========================================================================
    public function index(){

        $puertas = Puerta::orderBy('nombre')->get();

        foreach($puertas as $puerta){
            $puerta->users;
        }

        return response()->json($puertas,200);
    }
    // =========================================================================
    public function show($id){
        $puerta = Puerta::find($id);
        if(!isset($puerta)){
            throw new Exception('Could not find the apartment in database',400);
        }
            
        return response()->json($puerta,200);
        
    }
    // =========================================================================
    public function guardar(Request $request, $id){
       
        try {
            $body = $request->getContent();
            $puerta = json_decode($body);

            $puertaNueva = new Puerta;
            $puertaNueva->nombre = $puerta->nombre;
            $puertaNueva->camasTotal = $puerta->camasTotal;
            $puertaNueva->piso_id = $id;

            $puertaNueva->save();

            return response()->json(['message'=>'Door Added!'],200);

        } catch (Exception $exception) {

            throw new Exception('Could not save the Door in the database'.$exception->getMessage());
        }
       
    }
     // =========================================================================
     public function actualizar(Request $request){
        
        try {
          
            $body = $request->getContent();
            $data = json_decode($body);

            

            $puertaActualizar = Puerta::find($data->id);
            $alojados = count(usersEnPuerta($data->id));
            if($alojados>$data->camasTotal){
                $e = new  Exception($message = 'There is '.$alojados.' users in this apartment, you can not set less than '.$alojados.' beds.',$code=405);
                
                throw $e;
            }

            if(!isset($puertaActualizar)){
                throw new Exception('Could not find the door in database',400);
            }

            $puertaActualizar->nombre = $data->nombre;
            $puertaActualizar->camasTotal = $data->camasTotal;
            $puertaActualizar->update();    

            return response()->json(['message'=> 'Door Updated'],200);

        } catch (Exception $exception) {

            throw new Exception('Could not update the door in the database: '.$exception->getMessage(), $exception->getCode());
        }
    }
    // =============================================================================
    public function delete($id){
        
            try {
                $puerta = Puerta::find($id);
    
                if(!isset($puerta)){
                    throw new Exception('Could not find the door in database',400);
    
                }
                $piso = Piso::find($puerta->piso_id);

                $alojados = count(usersEnPuerta($puerta->id));
                if($alojados!=0){
                    throw new Exception('There is '.$alojados.' user/s in this apartment, you can not delete it.');
                }

                // $piso->camasEnBloque -=$puerta->camasTotal;
                // $piso->camasEnBloqueLibres -=$puerta->camasTotal;
                // $piso->update();

                $puerta->delete();
    
                return response()->json(["message"=> "Door Deleted"]);
                
    
            } catch (Exception $exception) {
    
                throw new Exception('Exception DeleteDoorMethod : '.$exception->getMessage());
            }
    }
    public function getRelated($piso_id){
        try {
            $puertas = Puerta::where('piso_id', $piso_id)->get();
            // $puertas->trabajos;
            foreach($puertas as $puerta){
                $puerta->users;
            }
            return response()->json($puertas);
        } catch (Exception $exception) {   
            throw new Exception('Error geting realated doors of the apartment'.$exception->getMessage());

        }
    }
        
       
}

function usersEnPuerta($puerta_id){
    $trabajos = Trabajo::where('puerta_id', $puerta_id)->get();
    $trabajosConPiso = [];
    $contador = 0;    
    foreach ($trabajos as $trabajo) {
        if($trabajo->puerta_id != null | $trabajo->puerta_id != 0) {
            $trabajosConPiso[$contador] = $trabajo;
        }
        $contador++;
    }
    return $trabajosConPiso;
}

   
   

   

   

 

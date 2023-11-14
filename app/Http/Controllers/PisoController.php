<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use App\Models\Puerta;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class PisoController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // =========================================================================
    public function index(){

        $pisos = Piso::all();
        
        foreach ($pisos as $piso){
            $puertas = Puerta::where('piso_id', $piso->id)->get();
            foreach ($puertas as $puerta){
                $puerta->users;
            }
            $piso->venue;
            $piso->puertas = $puertas;
            
        
        }

        return response()->json($pisos,200);
    }
    // =========================================================================
    public function show($id){

        $piso = Piso::find($id);

        if(!isset($piso)){

            throw new Exception('Apartment does not exists in database');

        }else{
            $piso->puertas;
            

            return response()->json($piso,200);
        }
    }
    // =========================================================================
    public function store(Request $request){
       
        $body = $request->getContent();
        $piso = json_decode($body);

        $pisoNuevo = new Piso;

        try {
            $pisoNuevo->nombre = $piso->nombre;
            $pisoNuevo->ciudad =  $piso->ciudad;
            $pisoNuevo->direccion = $piso->direccion;
            $pisoNuevo->venue_id = $piso->venue_id;
            
            $pisoNuevo->save();

        
            return response()->json(['message'=>'Apartment Added!'],200);

        } catch (Exception $exception) {

            throw new Exception('Error saving data, Apartment can not be stored'.$exception->getMessage(), $exception->getCode());

        }
       
    }
    // =============================================================================
    public function delete($id){
        try {

            $piso = DB::table("pisos")->where("id",$id)->delete();

            if(empty($piso)){

                throw new Exception('Apartment not found in database');


            }else{

                return response()->json(["message"=> "Apartment Deleted"]);
            }

        } catch (Exception $exception) {

            throw new Exception('Error deleting data, Apartment can not be deleted'.$exception->getMessage(), $exception->getCode());

        }
       
    }
    // =========================================================================
    public function actualizar(Request $request, $id){
        try {
            $body = $request->getContent();
            $pisoFront = json_decode($body);
            $pisoBack = Piso::find($id);

            if(!isset($pisoBack)){
                throw new Exception('Apartment not found in database');

            }

            $pisoBack->nombre = $pisoFront->nombre;
            $pisoBack->direccion = $pisoFront->direccion;
            $pisoBack->ciudad = $pisoFront->ciudad;
            $pisoBack->venue_id = $pisoFront->venue_id;
            $pisoBack->update();

            return response()->json(['message'=> 'Apartment Updated'],200);

        } catch (Exception $exception) {

            throw new Exception('Error updating data, Apartment can not be updated'.$exception->getMessage(), $exception->getCode());
        }
    }


    public function comprobarSiVenue($venue_id){
        try {
          
            $pisosBack = Piso::where('venue_id', $venue_id)->get();

            if(count($pisosBack)>0){
               return response()->json(['message' => 'hayPisos'],200);

            }else{
                return response()->json(['message' => 'noHayPisos'],202);
            }

        } catch (Exception $exception) {

            throw new Exception('Error finding Building with venue'.$exception->getMessage(), $exception->getCode());
        }
    } 

    public function relatedVenue($venue_id){
        
        try {
            $pisos = Piso::where('venue_id', $venue_id)->get();

            foreach ($pisos as $piso){
                $puertas = Puerta::where('piso_id', $piso->id)->get();
                foreach ($puertas as $puerta){
                    $puerta->users;
                }
                $piso->venue;
                $piso->puertas = $puertas;
                
            
            }
            return response()->json($pisos,200);
           
        } catch (Exception $exception) {

            throw new Exception('Error related Venue method'.$exception->getMessage());
        }
    }

   

   
}

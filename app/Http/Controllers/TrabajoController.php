<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;

use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;

class TrabajoController extends Controller
{
    public function getAll(){
        try {
            $trabajos = Trabajo::all();

            

            return response()->json($trabajos, 200);

        }catch (Exception $e){
            throw new Exception('Error getAll jobs'.$e->getMessage());
        }
    }

    public function getOne(int $id){
        try {
            $trabajo = Trabajo::find($id);

            if(!isset($trabajo)){
                throw new Exception('Could not find the job method->getOne('.$id.')');
            }
            $trabajo->puerta;
            
            return response()->json($trabajo, 200);

        }catch (Exception $e){
            throw new Exception('Error getOne jobs'.$e->getMessage());
        }
    }

   
    public function create(Request $request){
        try {
            $body = $request->getContent();
            $trabajoFront = json_decode($body);

            $trabajo = new Trabajo;

            $trabajo->user_id = $trabajoFront->user_id;
            $trabajo->venue_id = $trabajoFront->venue_id;
            $trabajo->role_id = $trabajoFront->role_id;
            $trabajo->departamento = $trabajoFront->departamento;
            
            $trabajo->fAlta = Carbon::parse($trabajoFront->fAlta);
            $trabajo->fBaja = Carbon::parse($trabajoFront->fBaja);
            
            if(isset($trabajoFront->piso_id)){
                $trabajo->piso_id = $trabajoFront->piso_id;
            }
            if(isset($trabajoFront->puerta_id)){
                $trabajo->puerta_id = $trabajoFront->puerta_id;
            }
            $trabajo->save();  
            
            ultimoTrabajo($trabajo->user_id);

            return response()->json(['message'=>'Job Created!'], 200);

        }catch (Exception $e){
            throw new Exception('Error Creating jobs'.$e->getMessage());
        }
    }

    public function delete($id){
        try {
            
            $trabajo = Trabajo::find($id);

            if(!isset($trabajo)){
                throw new Exception('Could not find the job method->Delete'.$id.')');
            }
           
            ultimoTrabajo($trabajo->user_id);
            $trabajo->delete();

            

            return response()->json(['message'=>'Job Deleted!'], 200);

        }catch (Exception $e){
            throw new Exception('Error deleting jobs'.$e->getMessage());
        }
    }

    public function getRelated($user_id){
        try {
           

            $trabajos = Trabajo::where('user_id', $user_id)->get();
            // if(count($trabajos) > 0){
            //     ultimoTrabajo($user_id);
            // }

            foreach ($trabajos as $trabajo){
                $trabajo->role;
                $trabajo->venue;

                
            }

            return response()->json($trabajos, 200);

        }catch (Exception $e){
            throw new Exception('Error getRelated jobs'.$e->getMessage());
        }
    }

    public function actualizar(Request $request){
        try {
            $body = $request->getContent();
            $trabajoFront = json_decode($body);

            $trabajo =Trabajo::find( $trabajoFront->id);

            if(!isset($trabajo)){
                throw new Exception('Could not find the job method->actualizar con id:('.$trabajoFront->id.')');
            }



            $trabajo->venue_id = $trabajoFront->venue_id;
            $trabajo->role_id = $trabajoFront->role_id;
            $trabajo->departamento = $trabajoFront->departamento;
            
            $trabajo->fAlta = Carbon::parse($trabajoFront->fAlta);
            $trabajo->fBaja = Carbon::parse($trabajoFront->fBaja);           
           
            if(isset($trabajoFront->piso_id)){
                $trabajo->piso_id = $trabajoFront->piso_id;
            }
            if(isset($trabajoFront->puerta_id)){
                $trabajo->puerta_id = $trabajoFront->puerta_id;
            }
            $trabajo->update();

            ultimoTrabajo($trabajo->user_id);

            return response()->json(['message'=>'Job Updated!'], 200);

        }catch (Exception $e){
            throw new Exception('Error Updating jobs'.$e->getMessage());
        }
    }
}

function ultimoTrabajo($user_id){
    $ultimoTrabajo = Trabajo::where('user_id',$user_id)->orderBy('fBaja','desc')->first();
    $hoy = Carbon::now();
    $user = User::find($ultimoTrabajo->user_id);
    //Si la fecha de baja es mayor que el día de hoy es que sigue de alta.
    if($ultimoTrabajo->fBaja > $hoy ){
        
        if($user->trabajo_id != $ultimoTrabajo->id){
            $user->trabajo_id = $ultimoTrabajo->id;
        }

    }else{
        $user->trabajo_id = 0;

    }
    $user->update();
    

}
<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use App\Models\Puerta;
use App\Models\Role;
use App\Models\Trabajo;
use App\Models\User;
use DateTime;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BadResponseException;
use Spatie\FlareClient\Http\Exceptions\BadResponse;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // =========================================================================
    public function index(){

       
        $usuarios = User::all();
        
        foreach ($usuarios as $usuario){
            
            $usuario->password = 'private';
            $trabajoUser = $usuario->trabajo;
            if(isset($trabajoUser)){
                $trabajo = Trabajo::find($usuario->trabajo_id);
                if(isset($trabajo)){
                    $usuario->role = $trabajo->role;
                    $usuario->venue = $trabajo->venue;
                    $usuario->working = true;
                    $trabajo = new Trabajo;
                }
            }else{
                $usuario->working = false;
            }
           
        }

        return response()->json($usuarios,200);
    }
    // =========================================================================
    public function get($id){
        
       try{
        $user = new User();
        $user = User::find($id);
        
         if(!isset($user)){
 
             return response()->json(["message"=> "User not Found method get(id)"],404);
 
         }else{
             $trabajo = $user->trabajo;
            if(isset($trabajo)){
                $puerta = Puerta::where('id', $trabajo->puerta_id)->first();
                $piso = Piso::where('id', $trabajo->piso_id)->first();
                if(isset($puerta)){
                    $user->puerta = $puerta;
                    $user->piso = $piso;
    
                }else{
                    $user->puerta = null;
                    $user->piso = null;
                }
            }
            

         $user->password = 'private';
             
             
             
         return response()->json($user,200);
 
         }
       }catch(Exception $e){
        throw new Error('Error Backend method  "User.pisoPuerta"'.$e->getMessage());

       }
       
    }
    // =========================================================================
    public function pisoPuerta($venue_id){
        try{
            $users = User::where('trabajo_id','!=',NULL)->orderBy('nombre','asc')->get();
            // $usersDevueltos = array();
            foreach ($users as $user){
                
                
                $trabajo = $user->trabajo;
                if(isset($trabajo)){
                    $puerta = Puerta::where('id', $trabajo->puerta_id)->first();
                    $piso = Piso::where('id', $trabajo->piso_id)->first();
                    if(isset($puerta)){
                        $user->puerta = $puerta;
                        $user->piso = $piso;

                    }else{
                        $user->puerta = null;
                        $user->piso = null;
                    }
                    $user->working = true;
                }
                $user->password = 'private';

                // if($trabajo->venue_id == $venue_id){
                //     $usersDevueltos[]= $user;
                // }

            }
            return response()->json($users,200);
        }catch(Exception $e){
            throw new Error('Error Backend method  "User.pisoPuerta"'.$e->getMessage());
        }
    }
    // =========================================================================
    public function store(Request $request){
        
        $body = $request->getContent();
        $user = json_decode($body);
        
       
        try {
            
           
            $password = Hash::make($user->password);

            if(isset($user->piso_id)){
                $piso_id = intVal($user->piso_id);
            }else{
                $piso_id = null;
            }

            if(isset($user->puerta_id)){
                $puerta_id = intVal($user->puerta_id);
            }else{
                $puerta_id = null;
            }
            $nuevoUsuario = new User;
            $nuevoUsuario->nombre = $user-> nombre;
            $nuevoUsuario->admin = $user->admin;
            $nuevoUsuario->apellidos = $user->apellidos;
            $nuevoUsuario->mail = $user->mail;
            $nuevoUsuario->sexo = $user->sexo;
            $nuevoUsuario->telefono = $user->telefono;
            $nuevoUsuario->superAdmin = $user->superAdmin;
            $nuevoUsuario->password = $password;
            $nuevoUsuario->trabajo_id = 0;
            $nuevoUsuario->save();

                return response()->json(['message'=>'User Added!'],200);

        } catch (Exception $exception) {

            throw new Error('Error saving data, Server in "Store" User'.$exception->getMessage());
           // return response()->json(['error'=> $exception->getMessage(),400]);
        }
       
    }
    public function update(Request $request,$id){
        try {  
            $body = $request->getContent();
            $user = json_decode($body);
            $usuarioActualizar = User::find($id);
            if(!isset($usuarioActualizar->id)){
                throw new Error('Could not find the user to update in database');
            }
            $usuarioActualizar->nombre = $user-> nombre;
            $usuarioActualizar->admin = $user->admin;
            $usuarioActualizar->apellidos = $user->apellidos;
            $usuarioActualizar->sexo = $user->sexo;
            $usuarioActualizar->telefono = $user->telefono;
            $usuarioActualizar->superAdmin = $user->superAdmin;
            if(isset($user->trabajo_id)){
                $usuarioActualizar->trabajo_id = $user->trabajo_id;
            }
            $usuarioActualizar->update();

                return response()->json(['message'=>'User Updated!'],200);


        } catch (Exception $exception) {
            throw new Error('Error updating User: '.$exception->getMessage(),$exception->getCode());
          //  throw new Error( $exception->getMessage(),$exception->getCode());

        }
    }
    // =========================================================================
    public function updatePiso(Request $request, $id){
        try {  
            $body = $request->getContent();
            $userFrontEnd = json_decode($body);

            $userBackend = User::find($userFrontEnd->id);

            if(!isset($userBackend->id)){
                throw new Error('Could not find the user to update in database');
            }
            
            $userBackend->puerta_id = intval($userFrontEnd->puerta_id);
            $userBackend->piso_id = intval($userFrontEnd->piso_id);
            $userBackend->update();
               
            
           

           
    
            return response()->json(['message'=>'User Assigned!'],200);


        } catch (Exception $exception) {
            throw new Error('Error updating User: '.$exception->getMessage());
          //  throw new Error( $exception->getMessage(),$exception->getCode());

        }
    }
    // =============================================================================
    public function delete($id){
        try {   
            $user = User::find($id);

            if(!isset($user)){

                throw new Error('Could not find the user to delete in database');

            }

            $user->delete();
            return response()->json(["message"=> "User Deleted"]);
            

        } catch (Exception $exception) {

            throw new Error('Error deleting User'. $exception->getMessage(), $exception->getCode());
        }
       
    }
    // =========================================================================
}

// function updatePisoYPuerta($piso_id, $puerta_id, $valor){
//     $piso = Piso::find($piso_id);
//     $piso->camasEnBloqueLibres -= $valor;
//     $piso->update();

//     $puerta = Puerta::find($puerta_id);
//     $puerta->camasOcupadas += $valor;
//     $puerta->update();
// }

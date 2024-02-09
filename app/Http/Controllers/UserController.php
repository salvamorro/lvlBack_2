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
use Carbon\Carbon;
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
    public function listadoInicial(){
        try{
            $users = User::all();

            foreach($users as $user){
                $trabajo = $user->trabajo;
                if(isset( $trabajo)){
                    $hoy = new Date();
                    if($trabajo->fBaja>$hoy){
                        $trabajo->activo = 1;
                    }else{
                        $trabajo->activo = 0;
                    }

                    $venue =  $trabajo->venue;

                    if(isset($venue)){
                        $user->venueNombre = $venue->nombre;
                    }
    
                    $role =  $trabajo->role;
    
                    if(isset($role)){
                        $user->roleNombre = $role->nombre;
                    }
                }else{
                    $user->venueNombre = '';
                    $user->roleNombre = '';
                }
               
            }
            

                 
             return response()->json($users,200);
     
        
           }catch(Exception $e){
            throw new Error('Error ListadoInicial User Controller:\r '.$e->getMessage());
    
           }
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
                $hoy = Carbon::now();
                if($trabajo->fBaja>$hoy){
                    $trabajo->activo = 1;
                }else{
                    $trabajo->activo = 0;
                }
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
            

        
             
             
             
         return response()->json($user,200);
 
         }
       }catch(Exception $e){
        throw new Error('Error Backend method  "User.pisoPuerta"'.$e->getMessage());

       }
       
    }
    // =========================================================================
    public function pisoPuerta(){
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
                // $user->password = 'private';

            }
            return response()->json($users,200);
        }catch(Exception $e){
            throw new Error('Error Backend method  "User.pisoPuerta"'.$e->getMessage());
        }
    }
     // =========================================================================
     public function pisoPuertaVenue($venue_id){
        try{
            $users = User::where('trabajo_id','!=',NULL)->orderBy('nombre','asc')->get();
            
            $usersDelVenue = [];
            
            foreach ($users as $user){
                
                $trabajo = $user->trabajo;
                if(isset($trabajo)){
                    if($trabajo->venue_id == $venue_id){
                        if(isset($trabajo)){
    
                            $puerta = Puerta::where('id', $trabajo->puerta_id)->first();
                           
                            
                            if(isset($puerta)){
                                $user->puerta = $puerta;
                                $user->piso = Piso::where('id', $trabajo->piso_id)->first();
    
                            }else{
                                $user->puerta = null;
                                $user->piso = null;
                            }
                            $user->working = true;
                        }
                    
                   
                        array_push($usersDelVenue, $user);
                    }
                }
                

            }
            return response()->json($usersDelVenue,200);
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
            $nuevoUsuario->adminPiso = $user->adminPiso;
            $nuevoUsuario->adminRRHH = $user->adminRRHH;
            $nuevoUsuario->adminPay = $user->adminPay;
            $nuevoUsuario->reciMails = $user->reciMails;
            
            $nuevoUsuario->apellidos = $user->apellidos;
            $nuevoUsuario->mail = $user->mail;
            if(!$this->mailUnico($nuevoUsuario->mail, $user->id)){
                throw new Error('This mail is being used, please select another.');
            }
            $nuevoUsuario->sexo = $user->sexo;
            $nuevoUsuario->telefono = $user->telefono;
            $nuevoUsuario->superAdmin = $user->superAdmin;
            $nuevoUsuario->password = $password;
            $nuevoUsuario->trabajo_id = 0;
            $nuevoUsuario->save();

                return response()->json($nuevoUsuario,200);

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
            $usuarioActualizar->adminPiso = $user->adminPiso;
            $usuarioActualizar->adminRRHH = $user->adminRRHH;
            $usuarioActualizar->adminPay = $user->adminPay;
            $usuarioActualizar->reciMails = $user->reciMails;
            
            $usuarioActualizar->mail = $user->mail;
            if($this->mailUnico($usuarioActualizar->mail, $user->id)){
               
            }else{
                throw new Error('This mail is being used, please select another.');
            }
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
    public function mailUnico($mail, $user_id):bool{
        $esUnico = false;

        $user = User::where('mail',$mail)->first();

        if(!isset($user) || $user->id == $user_id){
            $esUnico = true;
        }

        return $esUnico;

    }
    public function getResponsable($trabajo){
       $responsables = User::where('departamento',$trabajo->departamento)->where('admin',1)->get();

       return $responsables;

    }
}


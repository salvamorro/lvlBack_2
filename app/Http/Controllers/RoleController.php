<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // =========================================================================
    public function index(){

        $roles = Role::all();

        return response()->json($roles,200);
    }
    // =========================================================================
    public function show($id){
        $role = Role::find($id);
        if(!isset($role)){
            throw new Error('Could not find the role in database',400);
        }
            
        return response()->json($role,200);
        
    }
    // =========================================================================
    public function store(Request $request){
       
        try {
            $roleNuevo = new Role;
            
            $body = $request->getContent();
            $role = json_decode($body);
            $roleNuevo->nombre = $role->nombre;
            $roleNuevo->save();
            return response()->json(['message'=>'Role Added!'],200);

        } catch (Exception $exception) {

            throw new Error('Could not save the role in the database'.$exception->getMessage());
        }
       
    }
     // =========================================================================
     public function actualizar(Request $request, $id){
        try {
          
            $body = $request->getContent();
            $data = json_decode($body);

            $roleActualizar = Role::find($id);
            if(!isset($roleActualizar)){
                throw new Error('Could not find the role in database',400);
            }
            $roleActualizar->nombre = $data->nombre;
            $roleActualizar->update();    

            return response()->json(['message'=> 'Role Updated'],200);

        } catch (Exception $exception) {

            throw new Error('Could not update the role in the database'.$exception->getMessage());
        }
    }
    // =============================================================================
    public function delete($id){
        if(existenUsersConRole($id)){
            
            throw new Exception('There are users using this role, it can not be deleted');
        }else{
            try {
                $role = Role::find($id);
    
                if(!isset($role)){
                    throw new Error('Could not find the role in database',400);
    
                }
                $role->delete();
    
                return response()->json(["message"=> "Role Deleted"]);
                
    
            } catch (Exception $exception) {
    
                throw new Error('Could not delete the role in the database'.$exception->getMessage());
            }
        }
        
       
    }

   
   

   

   
}
 function existenUsersConRole($id){
    $users = User::all();
    $usersConRole = false;
    foreach ($users as $user){
        if($user->role_id == $id){
            $usersConRole = true;
        }   
    }
    return $usersConRole;
}

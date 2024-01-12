<?php

namespace App\Http\Controllers;


use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Namshi\JOSE\Signer\OpenSSL\HS256;

class LoginController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $sKey ;


    public function __construct(){
        $this->sKey = 'L@Cl@v3S3cr3T@098098';
    }

    public function login(Request $request){
    $user = new User();
    
    $user = DB::table("users")->where("mail", $request->input("mail"))->first();
    if(!$user){
        return response()->json(["message"=>'Mail not found'],400);
    }
    
    $passwordBruta = $request->input('password');
    
    $ok = Hash::check($passwordBruta, $user->password);

    if(!$ok){
        return response()->json(["message"=>'Mail OK. Password is wrong'],400);
    }
    
    $token  = [
        'id'=> $user->id,
        'nombre'=> $user->nombre,
        'apellidos' => $user->apellidos,
        'admin'=> $user->admin,
        'superAdmin'=> $user->superAdmin,
        'iat'=> time(),
        'expires' =>time()+(7*24*60*60),
        'iss' => 'http://example.org',
        'aud' => 'http://example.com',
    ] ;
        $jwt = JWT::encode($token, $this->sKey, 'HS384');

        return response()->json([$jwt, 200]);
    }

    public function cambiarPass($user_id,request $request){
        $body = $request->getContent();
        $pass = $body;


        try {
            $user = User::find($user_id);
            if(!isset($user)){
                throw new Exception('Can not find user');
            }
            $password = Hash::make($pass);
            $user->password = $password;
            $user->update();

            return response()->json(['message'=>'Password Changed!'], 200);

        } catch (Exception $exception) {
            throw new Exception('Error chaging pass '.$exception->getMessage(), $exception->getCode());
        }
       




    }

    public function decodePass(Request $request){

        try {
            $passss = $request->getContent();
            $pass = Hash::make($request->getContent());
            //$decrypt= Crypt::decrypt($user->password);  
            
            return response()->json([$pass, 200]);

        } catch (Exception $exception) {
            throw new Exception('Error DECODE login: '.$exception->getMessage(), $exception->getCode());
        }
      
        
    }

   

   
}

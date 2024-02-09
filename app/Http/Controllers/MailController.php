<?php

namespace App\Http\Controllers;

use App\Mail\AvisoIncidenciaAbierta;
use App\Mail\primeraNotificacion;
use App\Mail\RecordarPass;
use App\Mail\respuestaMail;
use App\Models\Doubt;
use App\Models\Inc;
use App\Models\Rrhh;
use App\Models\Trabajo;
use App\Models\User;
use App\Models\Venue;
use Error;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function nuevaInc(Request $request){

        $body = $request->getContent();
        $incidencia = json_decode($body);
        $inc = new Inc;
        $inc->id  = $incidencia->id;
        $inc->closed  = $incidencia->closed;
        $inc->foto  = $incidencia->foto;
        $inc->estado  = $incidencia->estado;
        $inc->nombre  = $incidencia->nombre;
        $inc->descripcion  = $incidencia->descripcion;
        $inc->user_id  = $incidencia->user_id;
        $inc->puerta_id  = $incidencia->puerta_id;
        $inc->piso_id  = $incidencia->piso_id;
        
        try {
            $user = User::find($inc->user_id);

            $trabajo = $user->trabajo;

            $responsables = $this->getResponsables($trabajo);
            foreach($responsables as $r){
                $mail = new primeraNotificacion($user, $inc->descripcion, $inc->nombre, "Appartment issue");

                Mail::to($r->mail)->send($mail);

            }

            return response()->json(['message'=>'Mail Sended'],200);

        } catch (Exception $exception) {

                throw new Exception('Error sending mail nuevaInc'.$exception->getMessage());

        }
    }
     public function recordarPass(Request $request){

        $body = $request->getContent();
        $mail = $body;
        try {
            $user = User::where('mail', $mail)->first();
            if(isset($user)){
                $token  = [
                    'id'=> $user->id,
                    'expires' =>time()+(60*60),
                ] ;

                $jwt = JWT::encode($token, 'L@Cl@v3S3cr3T@098098', 'HS384');

                Mail::to($mail)->send( new RecordarPass($jwt));
            }
            return response()->json(['message'=>'We have send a message to the mail: '.$mail.'. Please check your inbox.'],200);
    


            

        } catch (Exception $exception) {

            throw new Exception('Error method remember my pass:  '.$exception->getMessage());

        }
    }
    public function notificar(Request $request){
        $body = $request->getContent();
        $objeto = json_decode($body);

        $tipo = $objeto->tipo;
        $mensaje = $objeto->mensaje;
        $trabajo = $objeto->trabajo;

        
    }
    public function respuestaInc(Request $request){
        $body = $request->getContent();
        $respuesta = json_decode($body);
        $tipo = "appartment issue";
        try {
        $mensaje = $respuesta->mensaje;
        $inc = Inc::find($respuesta->inc_id);
        $user = $inc->user;
        if($respuesta->tipo == false || $respuesta->tipo == 0){
            //Es una respuesta del admin dirigida al usuario
            $mail = $user->mail;
            $user = new User;
            $user->nombre = 'Administrator';
            $user->apellidos = '';
            Mail::to($mail)->send(new respuestaMail($mensaje, $inc->nombre, $user,$tipo));
        }else{
            //Es una respuuesta del usuario dirigida a los admin
            $trabajo = $user->trabajo;
            $responsables = $this->getResponsables($trabajo);
            foreach($responsables as $responsable){
                Mail::to($responsable->mail)->send(new respuestaMail($mensaje, $inc->nombre, $user,$tipo));

            }

        }
       
          
        return response()->json(['message'=>'Mail Sended'],200);

        } catch (Exception $exception) {
            throw new Exception('Error sending mail  '.$exception->getMessage());
        }
    }
    public function nuevaRRHH(Request $request){
            $body = $request->getContent();
            $rrhh = json_decode($body);
            $tipo = "Leave Request";
            $titulo = "Request";
            try {
            $mensaje = $rrhh->descripcion;
               
            $user = User::find($rrhh->user_id);
            $trabajo = $user->trabajo;
            $responsables = $this->getResponsablesRRHH($trabajo);
            foreach($responsables as $responsable ){
              
                Mail::to($responsable->mail)->send(new primeraNotificacion($user,$mensaje,  $titulo, $tipo,));
            }
            
            return response()->json(['message'=>'Mail Sended'],200);
    
            } catch (Exception $exception) {
                throw new Exception('Error sending mail nuevaRRHH'.$exception->getMessage());
            }
        
    }
     public function respuestaRRHH(Request $request){
        $body = $request->getContent();
        $respuesta = json_decode($body);
        $tipo = " HHRR request";
        try {
        $mensaje = $respuesta->mensaje;
        $rrhh = Rrhh::find($respuesta->rrhh_id);
        $user = User::find($rrhh->user_id);
        if($respuesta->tipo == false || $respuesta->tipo == 0){
            //Es una respuesta del admin dirigida al usuario
            $mail = $user->mail;
            $user = new User;
            $user->nombre = 'Administrator';
            $user->apellidos = '';
            Mail::to($mail)->send(new respuestaMail($mensaje,'from '. $rrhh->start.' to '.$rrhh->end,$user, $tipo));
        }else{
            //Es una respuuesta del usuario dirigida a los admin
            $trabajo = $user->trabajo;
            $responsables = $this->getResponsablesRRHH($trabajo);
            foreach($responsables as $responsable){
                Mail::to($responsable->mail)->send(new respuestaMail($mensaje, 'from '. $rrhh->start.' to '.$rrhh->end, $user,$tipo));

            }

        }
       
          
        return response()->json(['message'=>'Mail Sended'],200);

        } catch (Exception $exception) {
            throw new Exception('Error sending mail  '.$exception->getMessage());
        }
    }
    public function nuevaDoubt(Request $request){
        $body = $request->getContent();
        $doubt = json_decode($body);
        $tipo = "Doubt with payslip";
        $titulo = "Doubt";
        try {
        $mensaje = $doubt->descripcion;
           
        $user = User::find($doubt->user_id);
        $trabajo = $user->trabajo;
        $responsables = $this->getResponsablesDoubt($trabajo);
        foreach($responsables as $responsable ){
          
            Mail::to($responsable->mail)->send(new primeraNotificacion($user,$mensaje,  $titulo, $tipo,));
        }
        
        return response()->json(['message'=>'Mail Sended'],200);

        } catch (Exception $exception) {
            throw new Exception('Error sending mail nuevaDoubt'.$exception->getMessage());
        }
    
    }
    public function respuestaDoubt(Request $request){
        $body = $request->getContent();
        $respuesta = json_decode($body);
        $tipo = "doubt";
        try {
        $mensaje = $respuesta->mensaje;
        $doubt = Doubt::find($respuesta->doubt_id);
        $user = User::find($doubt->user_id);
        if($respuesta->tipo == false || $respuesta->tipo == 0){
            //Es una respuesta del admin dirigida al usuario
            $mail = $user->mail;
            $user = new User;
            $user->nombre = 'Administrator';
            $user->apellidos = '';
            Mail::to($mail)->send(new respuestaMail($mensaje,$doubt->titulo, $user, $tipo));
        }else{
            //Es una respuuesta del usuario dirigida a los admin
            $trabajo = $user->trabajo;
            $responsables = $this->getResponsablesDoubt($trabajo);
            foreach($responsables as $responsable){
                Mail::to($responsable->mail)->send(new respuestaMail($mensaje, $doubt->titulo, $user, $tipo));

            }

        }
       
          
        return response()->json(['message'=>'Mail Sended'],200);

        } catch (Exception $exception) {
            throw new Exception('Error sending mail respuesta Doubt '.$exception->getMessage());
        }
    }
    public function pruebas($venue_id){
        try{
            $numero = intVal($venue_id);
            $trabajo = Trabajo::where('venue_id', $numero)->first();
            var_dump($this->getResponsables($trabajo)) ;
        }catch(Exception $e){
            throw new Error($e->getMessage());
        }
       
    }
    public function getResponsables(Trabajo $trabajo): array{
        $usersAdmin= User::where('adminPiso',1)->where('reciMails',1)->get();
       
        $responsables = [];
        foreach($usersAdmin as $user){
            $trabaja = $user->trabajo;
            if(isset($trabaja)){
                if($trabaja->venue_id == $trabajo->venue_id ){
                    array_push($responsables, $user);
                }
            }
        }
        if(count($responsables) == 0){
            $superAdmins = User::where('superAdmin', 1)->get();
            foreach($superAdmins as $user){
                $trabaja = $user->trabajo;
                if(isset($trabaja)){
                    if($trabaja->venue_id == $trabajo->venue_id ){
                        array_push($responsables, $user);
                    }
                }
            }
        }
        if(count($responsables) == 0){
            $superAdmins = User::where('superAdmin', 1)->get();
            foreach($superAdmins as $user){
               
                array_push($responsables, $user);
                
            }
        }

        return $responsables;
 
    }
    public function getResponsablesRRHH(Trabajo $trabajo): array{
        $usersAdmin = User::where('adminRRHH',1)->where('reciMails',1)->get();
        $responsables = [];
        foreach($usersAdmin as $user){
            $trabaja = $user->trabajo;
            if(isset($trabaja)){
                if($trabaja->venue_id == $trabajo->venue_id ){
                    array_push($responsables, $user);
                }
            }
        }
        if(count($responsables) == 0){
            $superAdmins = User::where('superAdmin', 1)->get();
            foreach($superAdmins as $user){
                $trabaja = $user->trabajo;
                if(isset($trabaja)){
                    if($trabaja->venue_id == $trabajo->venue_id ){
                        array_push($responsables, $user);
                    }
                }
            }
        }
        if(count($responsables) == 0){
            $superAdmins = User::where('superAdmin', 1)->get();
            foreach($superAdmins as $user){
               
                array_push($responsables, $user);
                
            }
        }
        
        return $responsables;
 
    }
    public function getResponsablesDoubt(Trabajo $trabajo): array{
        $usersAdmin = User::where('adminPay',1)->where('reciMails',1)->get();
        $responsables = [];
        foreach($usersAdmin as $user){
            $trabaja = $user->trabajo;
            if(isset($trabaja)){
                if($trabaja->venue_id == $trabajo->venue_id ){
                    array_push($responsables, $user);
                }
            }
        }
        if(count($responsables) == 0){
            $superAdmins = User::where('superAdmin', 1)->get();
            foreach($superAdmins as $user){
                $trabaja = $user->trabajo;
                if(isset($trabaja)){
                    if($trabaja->venue_id == $trabajo->venue_id ){
                        array_push($responsables, $user);
                    }
                }
            }
        }
        if(count($responsables) == 0){
            $superAdmins = User::where('superAdmin', 1)->get();
            foreach($superAdmins as $user){
               
                array_push($responsables, $user);
                
            }
        }
        
        return $responsables;

    }
   

}

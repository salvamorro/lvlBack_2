<?php

use App\Http\Controllers\DoubtController;
use App\Http\Controllers\IncController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PuertaController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PisoController;
use App\Http\Controllers\RespuestaController;
use App\Http\Controllers\RespuestaDoubtController;
use App\Http\Controllers\RespuestaRRHHController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RrhhController;
use App\Http\Controllers\VenueController;
use App\Models\Doubt;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
//     return $request->user();
// });
// =============================================================================ROLES

Route::get("role/",[RoleController::class,"index"]);
Route::get("role/{id}",[RoleController::class,"show"]);
Route::post("role/",[RoleController::class,"store"]);
Route::post("role/{id}", [RoleController::class,"actualizar"]);
Route::get("role/delete/{id}", [RoleController::class,"delete"]);

// =============================================================================PISOS
Route::get("piso/",[PisoController::class,"index"]);
Route::get("piso/relatedVenue/{id}",[PisoController::class,"relatedVenue"]);
Route::get("piso/{id}",[PisoController::class,"show"]);
Route::post("piso/",[PisoController::class,"store"]);
Route::post("piso/update/{id}", [PisoController::class,"actualizar"]);
Route::delete("piso/{id}", [PisoController::class,"delete"]);
Route::get("piso/venue/{id}", [PisoController::class,"comprobarSiVenue"]);

// =============================================================================INCS
Route::get("inc/", [IncController::class,"index"]);
Route::get("inc/{id}",[IncController::class,"show"]);
Route::post("inc/",[IncController::class,"store"]);
Route::get("inc/delete/{id}", [IncController::class,"delete"]);
Route::post("inc/{id}", [IncController::class,"actualizar"]);
Route::get( "inc/user/{id}", [IncController::class,"getRelated"]);
Route::get("inc/sendMail/send/", [IncController::class,"sendMail"]);


// =============================================================================RESPUESTAS

Route::get("respuesta/", [RespuestaController::class,"index"]);
Route::get("respuesta/{id}", [RespuestaController::class,"show"]);
Route::post("respuesta/", [RespuestaController::class,"store"]);
Route::get("respuesta/delete/{id}", [RespuestaController::class,"delete"]);
Route::post("respuesta/{id}", [RespuestaController::class,"actualizar"]);


// =============================================================================USERS

Route::get("user/", [UserController::class,"index"]);
Route::get("user/{id}", [UserController::class,"show"]);
Route::get("user/{id}", [UserController::class,"get"]);
Route::get("user/piso/puerta/", [UserController::class,"pisoPuerta"]);
Route::get("user/delete/{id}", [UserController::class,"delete"]);
Route::get("user/listado/inicial",[UserController::class,"listadoInicial"]);
Route::post("user/", [UserController::class,"store"]);
Route::post("user/{id}", [UserController::class,"update"]);
Route::post("user/piso/{id}", [UserController::class,"updatePiso"]);


// =============================================================================LOGIN
Route::post("login/", [LoginController::class,"login"]);
Route::put("login/cambiarPass/{user_id}", [LoginController::class, "cambiarPass"]);
// =============================================================================PUERTA
Route::get("puerta/", [PuertaController::class,"index"]);
Route::get("puerta/{id}", [PuertaController::class,"show"]);
Route::post("puerta/piso/{id}", [PuertaController::class,"guardar"]);
Route::get("puerta/delete/{id}", [PuertaController::class,"delete"]);
Route::put("puerta/", [PuertaController::class,"actualizar"]);
Route::get( "puerta/piso/{id}", [PuertaController::class,"getRelated"]);
// ============================================================================= TRABAJO
Route::get("trabajo/", [TrabajoController::class,"getAll"]);
Route::get("trabajo/{id}", [TrabajoController::class,"getOne"]);
Route::post("trabajo/", [TrabajoController::class,"create"]);
Route::put("trabajo/",[TrabajoController::class,"actualizar"]);
Route::get("trabajo/user/{id}", [TrabajoController::class,"getRelated"]);
Route::delete("trabajo/{id}", [TrabajoController::class,"delete"]);

// ============================================================================= VENUE
Route::get("venue/",[VenueController::class,"index"]);
Route::get("venue/{id}",[VenueController::class,"show"]);
Route::post("venue/",[VenueController::class,"store"]);
Route::put("venue/", [VenueController::class,"actualizar"]);
Route::delete("venue/{id}", [VenueController::class,"delete"]);

// =============================================================================RRHH
Route::get("rrhh/", [RrhhController::class,"index"]);
Route::get("rrhh/{id}",[RrhhController::class,"show"]);
Route::post("rrhh/",[RrhhController::class,"store"]);
Route::delete("rrhh/{id}", [RrhhController::class,"delete"]);
Route::put("rrhh/", [RrhhController::class,"actualizar"]);
Route::get( "rrhh/user/{id}", [RrhhController::class,"getRelated"]);

// =============================================================================RESPUESTASRRHH

Route::get("respuestaRRHH/", [RespuestaRRHHController::class,"index"]);
Route::get("respuestaRRHH/{id}", [RespuestaRRHHController::class,"show"]);
Route::post("respuestaRRHH/", [RespuestaRRHHController::class,"store"]);
Route::delete("respuestaRRHH/{id}", [RespuestaRRHHController::class,"delete"]);
Route::put("respuestaRRHH/", [RespuestaRRHHController::class,"actualizar"]);



 // ========================================================================== MAIL
 Route::post("mail/nuevaInc/", [MailController::class,"nuevaInc"]);
 Route::post("mail/changePass/", [MailController::class,"recordarPass"]);
 Route::post("mail/notificar/", [MailController::class, "notificar"]);
 Route::post("mail/respuesta/", [MailController::class, "respuestaInc"]);
 Route::post("mail/nuevaRRHH/", [MailController::class, "nuevaRRHH"]);
 Route::post("mail/respuestaRRHH/", [MailController::class, "respuestaRRHH"]);
 Route::post("mail/nuevaDoubt/", [MailController::class, "nuevaDoubt"]);
 Route::post("mail/respuestaDoubt/", [MailController::class, "respuestaDoubt"]);

// DOUBT =======================================================================
Route::get("doubt/", [DoubtController::class,"index"]);
Route::get("doubt/{id}",[DoubtController::class,"show"]);
Route::post("doubt/",[DoubtController::class,"store"]);
Route::delete("doubt/", [DoubtController::class,"delete"]);
Route::put("doubt/", [DoubtController::class,"actualizar"]);
Route::get( "doubt/user/{id}", [DoubtController::class,"getRelated"]);
 
// =============================================================================RESPUESTASDOUBT

Route::get("respuestaDoubt/", [RespuestaDoubtController::class,"index"]);
Route::get("respuestaDoubt/{id}", [RespuestaDoubtController::class,"show"]);
Route::post("respuestaDoubt/", [RespuestaDoubtController::class,"store"]);
Route::delete("respuestaDoubt/{id}", [RespuestaDoubtController::class,"delete"]);
Route::put("respuestaDoubt/", [RespuestaDoubtController::class,"actualizar"]);

// ============================================================================= PRUEBAS
Route::post("decode/", [LoginController::class, "decodePass"]);
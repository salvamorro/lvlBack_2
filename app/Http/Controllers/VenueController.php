<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\Translation\Dumper\DumperInterface;

class VenueController extends Controller
{
    public function index(){
        try{
            $venues= Venue::all();

            return response()->json($venues, 200);

        }catch(Exception $e){
            throw new Exception('Error Reading Venues: '.$e->getMessage());
        }

    }
   
    public function show($id){

    }
    public function store(Request $request){
        try{
            $body = $request->getContent();
            $venueFront = json_decode($body);
            $venue= new Venue;

            $venue->nombre= $venueFront->nombre;

            $venue->save();
            $res = ['message'=>'Venue Saved!' ];
            return response()->json($res, 200);

        }catch(Exception $e){
            throw new Exception('Error Saving Venue: '.$e->getMessage());
        }
    }

    public function actualizar(Request $request){
        try{
            $body = $request->getContent();
            $venueFront = json_decode($body);
            $venue=Venue::find($venueFront->id);

            if(!isset($venue)){
                throw new Exception('Venue to update not found');
            }

            $venue->nombre= $venueFront->nombre;

            $venue->update();
            
            return response()->json(['message'=>'Venue Updated!'], 200);

        }catch(Exception $e){
            throw new Exception('Error Updating Venue: '.$e->getMessage());
        }
    }
    public function delete($id){
        try{
            
            $venue=Venue::find($id);

            if(!isset($venue)){
                throw new Exception('Venue to delete not found');
            }

            $venue->delete();
            
            return response()->json(['message'=>'Venue Deleted!'], 200);

        }catch(Exception $e){
            throw new Exception('Error Deleting Venue: '.$e->getMessage());
        }

    }
}

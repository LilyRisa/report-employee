<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemConf;
use App\Providers\Requestapi;
use Illuminate\Support\Facades\DB;
class SyncController extends Controller
{
    public function index(){
        $request_list_thermal = new Requestapi('/api/v1/thermal-list');
        $request_list_thermal = $request_list_thermal->methodGet();
        $request_list_thermal = \json_decode($request_list_thermal);

        $request = new Requestapi('/api/v1/thermal/library');
        $thermal = [];
        foreach($request_list_thermal->thermalInfo as $ther){
            try{
                $thermal[$ther->name] = json_decode($request->getResponse(['name' => $ther->name]));
            }catch(\GuzzleHttp\Exception\BadResponseException $e){
                $thermal[$ther->name] = null;
            }
            
        }
        //dd($thermal);
        return view('sync',['data' => $thermal]);
    }

    public function getLibrary($ip){
        $data = SystemConf::findOrFail(1);
        $server_ip = $data->Server_ip;
        $hiface_ip = $data->hiface_ip;
        $data = explode(',',$data->thermal_ip);
        //dd($data);
        $request = new Requestapi('/api/v1/thermal/library');
        // $ther_ip = array_map(function($data,$request){
        //     return $request->getResponse(['thermal_ip' => $data]);
        // },$data,$request);
        $ther_ip = array();
        foreach($data as $val_ther){
            try{
                $ther_ip[$val_ther] = json_decode($request->getResponse(['thermal_ip' => $val_ther]));
            }catch(\GuzzleHttp\Exception\BadResponseException $e){
                $ther_ip[$val_ther] = null;
            }
            
        }
      return \response()->json($ther_ip);
    }

    public function Sync(Request $request){
        $data = $request->input('request');
        $sync = new Requestapi('/api/v1/sync');
        $respon = [];
        foreach($data as $value){
            try{
                $respon[] = json_decode($sync->getResponse($value));
            }catch(\GuzzleHttp\Exception\BadResponseException $e){
                $respon[] = 'fail';
            }
        }
        $check = array_search('fail',$respon);
       //dd($data);
       if($check != null){
        return \response()->json(['error' => 0]);
       }else{
        return \response()->json(['error' => 1]);
       }
        
    }
}

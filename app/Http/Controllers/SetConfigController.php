<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\Requestapi;

class SetConfigController extends Controller
{
    public function index(){
        $respon = new Requestapi('/api/v1/config');
        try{
            $data = $respon->methodGet(null);
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            $data = null;
        }
        if($data != null){
            $data = \json_decode($data);
        }else{
            $data = null;
        }
        return view('system',['data' => $data]);
    }

    public function post(Request $request){
        $thermal_ip = explode(',',$request->input('thermal_ip'));
        $arr = [];
        foreach($thermal_ip as $k => $v){
            $j = $k +1;
            $arr[] = [
                'name' => "Thermal_".$j,
                'ip' => $v
            ];
        }
        $data = array("hiface_info" => [
                    "ip" => $request->input('hiface_ip'),
                    "username" => $request->input('username'),
                    "password" => $request->input('password')
                ],
                "thermal_info" => $arr
            ); 
        $respon = new Requestapi('/api/v1/config');
        try{
            $data = json_decode($respon->getResponse($data));
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            $data = ['code' => 1];
        }

        return \response()->json($data);
    }
        

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SystemConf;

class SystemController extends Controller
{
    public function index(){
        $data =  DB::Table('systemconfig')->select('Server_ip','thermal_ip','hiface_ip','username','password')->where('id',1)->get();
        return view('system',['data' => $data[0]]);
    }

    public function post(Request $request){
        $sys = SystemConf::find(1);
        $sys->Server_ip = $request->input('Server_ip');
        $sys->thermal_ip = $request->input('thermal_ip');
        $sys->hiface_ip = $request->input('hiface_ip');
        $sys->username = $request->input('username');
        $sys->password = $request->input('password');
        $sys->save();
        return Response()->json(['status' => 200,'messenges'=>'']); 
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\Requestapi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private function requestapi($time_start, $time_end){  //function call api 
        $table =  DB::Table('systemconfig')->select('thermal_ip','hiface_ip','username','password')->where('id',1)->get();
        $time_start = (int)$time_start;
        $time_end = (int)$time_end;
        $response = new Requestapi('/api/v1/report/json');
        $data = $response->getResponse([
            "start"=> ((int)$time_start)*1000,
            "end"=> ((int)$time_end)*1000,
            "thermal_ip"=> $table[0]->thermal_ip,
            "hiface_ip"=> $table[0]->hiface_ip,
            "username"=> $table[0]->username,
            "password"=> $table[0]->password
        ]);
        $data = json_decode($data);
        foreach($data as $key => $value) //set value in object item
        {
            $data[$key]->temperature = number_format((float)$data[$key]->temperature, 1, '.', '');
            $data[$key]->time_checkin = $data[$key]->time_checkin != null ? Carbon::createFromTimestamp(intval($data[$key]->time_checkin/1000))->timezone('asia/ho_chi_minh')->format('H:i:s Y-m-d') : null;
            $data[$key]->time_checkout = $data[$key]->time_checkout != null ? Carbon::createFromTimestamp(intval($data[$key]->time_checkout/1000))->timezone('asia/ho_chi_minh')->format('H:i:s Y-m-d') : null;
            //$data[$key]->image_path = 'data:image/jpeg;base64,'.base64_encode(file_get_contents('http://192.168.51.213'.$data[$key]->image_path));
            $data[$key]->image_path = 'http://'.$table[0]->hiface_ip.$data[$key]->image_path;
        }
        return $data;
    }


    public function index(){
        $time = Carbon::now()->format('d-m-yy');
        $time = explode('-',$time);
        $time_start_now = json_encode(Carbon::create($time[2], $time[1], $time[0], 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $time_end_now = json_encode(Carbon::create($time[2], $time[1], ((int)$time[0]) +1, 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $data = $this->requestapi($time_start_now,$time_end_now);
        //dd(count($data));
        return view('home',['count_employee_today' => count($data)]);
    }
    
    public function test(){ //test data
        $data = '11/12/2020';
        $data = explode('/',$data);
        $return = Carbon::create($data[2], $data[1], $data[0], 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]);

        //$data = Carbon::createFromFormat(,'UTC')->getTimestamp();
        dd(json_encode($return));
    }
    public function employee_index(){
        return view('employee');
    }

    public function employee_get(){ //lấy dữ liệu ban đầu
        $time = Carbon::now()->format('d-m-yy');
        $time = explode('-',$time);
        $time_start_now = json_encode(Carbon::create($time[2], $time[1], $time[0], 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $time_end_now = json_encode(Carbon::create($time[2], $time[1], ((int)$time[0]) +1, 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $data = $this->requestapi($time_start_now,$time_end_now);
        return response()->json($data);
    }

    public function employeeraw(){
        $mytime = Carbon::now()->format('d-m-yy');
        $data = $this->requestapi('1605114000','1605196800');
        return view('RawEmployee',['timenow' => $mytime]);
    }

    public function Postemployeeraw(Request $request){ // lấy data timerange
        $data = explode(" - ",$request->input('timerange'));
        $start_time = $data[0];
        $end_time = $data[1];
        $start_time = explode('/',$start_time); //convert string time to timestamp unix 
        $start_time_stamp =  json_encode(Carbon::create($start_time[2], $start_time[1], $start_time[0], 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ])); 
        $end_time = explode('/',$end_time);
        $end_time_stamp =  json_encode(Carbon::create($end_time[2], $end_time[1], ((int)$end_time[0]) +1, 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $data_api = $this->requestapi($start_time_stamp, $end_time_stamp);
        return response()->json($data_api);
        //return response()->json(['start' => $start_time_stamp, 'end' => $end_time_stamp]);
    }

    // get count today

    public function getTodayCount(){
        $time = Carbon::now()->format('d-m-yy');
        $time = explode('-',$time);
        $time_start_now = json_encode(Carbon::create($time[2], $time[1], $time[0], 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $time_end_now = json_encode(Carbon::create($time[2], $time[1], ((int)$time[0]) +1, 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $data = $this->requestapi($time_start_now,$time_end_now);
        //dd(count($data));
        return response()->json(['count_today' => count($data)]);
    }
}

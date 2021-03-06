<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\Requestapi;
use Carbon\Carbon;
use Config;

class HomeController extends Controller
{
    private function requestapi($time_start, $time_end, $thermal){  //function call api 
        $respon = new Requestapi('/api/v1/config');
        try{
            $conf = json_decode($respon->methodGet(null));
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            $conf = null;
        }
        $time_start = (int)$time_start;
        $time_end = (int)$time_end;
        $response = new Requestapi('/api/v1/report/json');
        $data = $response->getResponse([
            'time_range' => [
                "start"=> ((int)$time_start)*1000,
                "end"=> ((int)$time_end)*1000,
            ],
            'thermal_name_list' => $thermal
            
        ]);
        $data = json_decode($data);
        foreach($data as $key => $value) //set value in object item
        {
            //$data[$key]->temperature = number_format((float)$data[$key]->temperature, 1, '.', '');
            $data[$key]->time_checkin = $data[$key]->time_checkin != null ? Carbon::createFromTimestamp(intval($data[$key]->time_checkin/1000))->timezone('asia/ho_chi_minh')->format('H:i:s Y-m-d') : null;
            $data[$key]->time_checkout = $data[$key]->time_checkout != null ? Carbon::createFromTimestamp(intval($data[$key]->time_checkout/1000))->timezone('asia/ho_chi_minh')->format('H:i:s Y-m-d') : null;
            //$data[$key]->image_path = 'data:image/jpeg;base64,'.base64_encode(file_get_contents('http://192.168.51.213'.$data[$key]->image_path));
            $data[$key]->entry_date = $data[$key]->entry_date != null ? Carbon::createFromTimestamp(intval($data[$key]->entry_date/1000))->timezone('asia/ho_chi_minh')->format('H:i:s Y-m-d') : null;
            $data[$key]->date = $data[$key]->date != null ? Carbon::createFromTimestamp(intval($data[$key]->date/1000))->timezone('asia/ho_chi_minh')->format('H:i:s Y-m-d') : null;
            $data[$key]->birthday = $data[$key]->birthday != null ? Carbon::createFromTimestamp(intval($data[$key]->birthday/1000))->timezone('asia/ho_chi_minh')->format('Y-m-d') : null;
            $data[$key]->image_path = 'http://'.$conf->hiface_info->ip.$data[$key]->image_path;
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

        $data = new Requestapi('/api/v1/number/employee');
        try{
            $data = json_decode($data->getResponse([
                'start' => ((int)$time_start_now)*1000,
                'end' => ((int)$time_end_now)*1000
            ]));
            $data = $data->number_of_employee;
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            $data = null;
        }
        //dd($data);
        //dd($time_start_now);
        return view('home',['count_employee_today' => $data]);
    }
    
    public function test(){ //test data
    }

    public function employee_index(){
        
        return view('employee');
    }

    public function employee_get(){ //lấy dữ liệu ban đầu
        // dd();
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
        $thermal_list_check = new Requestapi('/api/v1/thermal-list');
        $thermal_list_check = json_decode($thermal_list_check->methodGet());
        $thermal_check_null = new Requestapi('/api/v1/thermal/library');
        $thermal_list = [];
        foreach($thermal_list_check->thermalInfo as $k => $v){
            $thermal_list[] = $v->name;
        }
            //dd(array_values($thermal_list_check->thermalInfo));
            try{
                $data = $this->requestapi($time_start_now,$time_end_now, $thermal_list);
            }catch(\GuzzleHttp\Exception\BadResponseException $e){
                $data = [];
            }
        
        return response()->json($data);
    }

    public function employeeraw(){
        $response = new Requestapi('/api/v1/thermal-list');
        $ther_list = $response->methodGet();
        $ther_list = \json_decode($ther_list);
        $mytime = Carbon::now()->format('d-m-yy');
        //dd($ther_list);
        return view('RawEmployee',['timenow' => $mytime, 'data' => $ther_list]);
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

        try{
            $data_api = $this->requestapi($start_time_stamp, $end_time_stamp,[$request->input('thermal')]);
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            $data_api = [];
        }
       
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
        
        $data = new Requestapi('/api/v1/number/employee');
        try{
            $data = json_decode($data->getResponse([
                'start' => ((int)$time_start_now)*1000,
                'end' => ((int)$time_end_now)*1000
            ]));
            $data = $data->number_of_employee;
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            $data = null;
        }

        $remain = new Requestapi('/api/v1/number/employee-remaining');
        try{
            $remain = json_decode($remain->getResponse([
                'start' => ((int)$time_start_now)*1000,
                'end' => ((int)$time_end_now)*1000
            ]));
            $remain = $remain->number_of_employee;
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            $remain = null;
        }
        return response()->json(['count_employee_today' => $data, 'number_of_employee' => $remain]);
    }
}

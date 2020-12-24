<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\Requestapi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Response;
use Config;

class ReportController extends Controller
{

    public function ExcelReport(){
        $thermal_list_check = new Requestapi('/api/v1/thermal-list');
        $thermal_list_check = json_decode($thermal_list_check->methodGet());
        $thermal_list = [];
        foreach($thermal_list_check->thermalInfo as $k => $v){
            $thermal_list[] = $v->name;
        }

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
        $opts = array('http' =>
            array(
                'method'  => 'GET',
                'header'  => 'Content-Type: application/json',
                'content' => json_encode(array(
                    "start"=> ((int)$time_start_now)*1000,
                    "end"=> ((int)$time_end_now)*1000,
                    "thermal_name_list"=> $thermal_list,
                ))
            )
        );
        $context  = stream_context_create($opts);
        $data = file_get_contents(config('app.SERVER_IP').'/api/v1/report/excel', false, $context);
        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
          );
          //dd($data);
        return Response($data)->header('Cache-Control', 'no-cache private')
        ->header('Content-Description', 'File Transfer')
        ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ->header('Content-length', strlen($data))
        ->header('Content-Disposition', 'attachment; filename=Excel-reports.xlsx')
        ->header('Content-Transfer-Encoding', 'binary');

        
    }

    public function ExcelReportTimeRange($time_start , $time_end , $thermal){

        $opts = array('http' =>
        array(
            'timeout' => 1200,
            'method'  => 'GET',
            'header'  => 'Content-Type: application/json',
            'content' => json_encode(array(
                'time_range' => [
                    "start"=> ((int)$time_start)*1000,
                    "end"=> ((int)$time_end)*1000,
                ],
                'thermal_name_list' => [$thermal],
                ))
            )
        );
        $context  = stream_context_create($opts);
        $data = file_get_contents(config('app.SERVER_IP').'/api/v1/report/excel?report_type=thermal_report', false, $context);
        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );
        //dd($data);
        return Response($data)->header('Cache-Control', 'no-cache private')
        ->header('Content-Description', 'File Transfer')
        ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ->header('Content-length', strlen($data))
        ->header('Content-Disposition', 'attachment; filename=Excel-reports-'.Carbon::createFromTimestamp(intval($time_start))->timezone('asia/ho_chi_minh')->format('Y-m-d').' | '.Carbon::createFromTimestamp(intval($time_end))->timezone('asia/ho_chi_minh')->format('Y-m-d').'.xlsx')
        ->header('Content-Transfer-Encoding', 'binary');

    }

    public function PassVar(Request $request){
        $start_time = explode('/',$request->input('start'));
        $end_time = explode('/',$request->input('end'));
        $thermal = $request->input('thermal');
        $start_time_stamp =  json_encode(Carbon::create($start_time[2], $start_time[1], $start_time[0], 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $end_time_stamp =  json_encode(Carbon::create($end_time[2], $end_time[1], ((int)$end_time[0]) +1, 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));

        $link = route('excel_report_time',['time_start' => $start_time_stamp, 'time_end' => $end_time_stamp, 'thermal' => $thermal]);
        return response()->json(array('link' => $link));
    }
    public function PassVarRaw(Request $request){
        $start_time = explode('/',$request->input('start'));
        $end_time = explode('/',$request->input('end'));
        $thermal = $request->input('thermal');
        $start_time_stamp =  json_encode(Carbon::create($start_time[2], $start_time[1], $start_time[0], 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));
        $end_time_stamp =  json_encode(Carbon::create($end_time[2], $end_time[1], ((int)$end_time[0]) +1, 00, 00, 00, 'asia/ho_chi_minh')->settings([
            'toJsonFormat' => function ($date) {
                return $date->getTimestamp();
            },
        ]));

        $link = route('excel_report_time_raw',['time_start' => $start_time_stamp, 'time_end' => $end_time_stamp, 'thermal' => $thermal]);
        return response()->json(array('link' => $link));
    }
    public function ExcelReportTimeRangeRaw($time_start , $time_end , $thermal){
        $opts = array('http' =>
        array(
            'timeout' => 1200,
            'method'  => 'GET',
            'header'  => 'Content-Type: application/json',
            'content' => json_encode(array(
                'time_range' => [
                    "start"=> ((int)$time_start)*1000,
                    "end"=> ((int)$time_end)*1000,
                ],
                'thermal_name_list' => [$thermal],
                ))
            )
        );
        $context  = stream_context_create($opts);
        $data = file_get_contents(config('app.SERVER_IP').'/api/v1/report/excel?report_type=raw_data_employee', false, $context);
        $headers = array(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        );
        //dd($data);
        return Response($data)->header('Cache-Control', 'no-cache private')
        ->header('Content-Description', 'File Transfer')
        ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ->header('Content-length', strlen($data))
        ->header('Content-Disposition', 'attachment; filename=Excel-reports-'.Carbon::createFromTimestamp(intval($time_start))->timezone('asia/ho_chi_minh')->format('Y-m-d').' | '.Carbon::createFromTimestamp(intval($time_end))->timezone('asia/ho_chi_minh')->format('Y-m-d').'.xlsx')
        ->header('Content-Transfer-Encoding', 'binary');

    }
}

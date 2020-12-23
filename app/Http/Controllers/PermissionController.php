<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\Requestapi;

class PermissionController extends Controller
{
    public function index(){
        $response = new Requestapi('/api/v1/hiface/group/list?page=1&size=200');
        try{
            $data = json_decode($response->methodGet());
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            $data = null;
        }
        $data_js = $data != null ? $data->data : null;
        if($data_js != null){
            foreach($data_js as $key => $value){
                if($data->data[$key]->subject_type == 0){
                    $data_js[$key]->subject_type = 'Staff group';
                }else{
                    $data_js[$key]->subject_type = 'Visitor group';
                }
            }
        }
        $response = new Requestapi('/api/v1/hiface/constants?category=employee');
        try{
            $data = json_decode($response->methodGet());
        }catch(\GuzzleHttp\Exception\BadResponseException $e){
            $data = null;
        }
        $data_depart = $data != null ? $data->data->department : null;
        
        //dd($data_js);
        return view('permiss',['data' => $data_js, 'data_depart' => $data_depart]);
    }

    public function GetDPinGR(Request $request){
        $id = $request->input('id_gr');
        $response = new Requestapi('/api/v1/hiface/group/'.$id.'/department');
        $data = json_decode($response->methodGet());
        return \response()->json($data->department_list_in_group);
    }

    public function SaveDepartmentInGroup(Request $request){
        
        $sp_in_group = new Requestapi('/api/v1/hiface/group/'.$request->input('group').'/department');
        $sp_in_group = json_decode($sp_in_group->methodGet());
        $sp_old_in_group = $sp_in_group->department_list_in_group;

        $diff = array_intersect($sp_old_in_group,$request->input('department'));
        // get department delete in ajax
        $diff_old = array_diff($sp_old_in_group,$diff);
        // delete department
        $arr_del_diff = [];
        $return = [];
        if(!empty($diff_old)){
            foreach($diff_old as $dif){
                $resq = new Requestapi('/api/v1/hiface/list?category=employee&department='.$dif.'&page=1&size=1000');
                $resq = json_decode($resq->methodGet());
                foreach($resq->data as $dif_val){
                    $arr_del_diff[] = $dif_val->id;
                }
            }
            $req = new Requestapi('/api/v1/hiface/group/'.$request->input('group').'/delete');
            $return['del'] =  json_decode($req->getResponse([
                'subject_ids' => $arr_del_diff,
            ]));
        }
        
        // insert department to group 
        $diff_new = array_diff($request->input('department'),$diff);
        $arr_insert_diff = [];
        if(!empty($diff_new)){
            foreach($diff_new as $new){
                $resq = new Requestapi('/api/v1/hiface/list?category=employee&department='.$new.'&page=1&size=1000');
                $resq = json_decode($resq->methodGet());
                foreach($resq->data as $dif_v){
                    $arr_insert_diff[] = $dif_v->id;
                }
            }
            $req = new Requestapi('/api/v1/hiface/group/'.$request->input('group').'/insert');
            $return['insert'] =  json_decode($req->getResponse([
                'subject_ids' => $arr_insert_diff,
            ]));
        }
        $reponse_serve = [];
        if(isset($return['insert'])){
            $reponse_serve['insert'] = $return['insert'];
        }
        if(isset($return['del'])){
            $reponse_serve['delete'] = $return['del'];
        }
        return \response()->json($reponse_serve);
    }
}

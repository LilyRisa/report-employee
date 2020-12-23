<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\Requestapi;

class SubjectPermisionController extends Controller
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
        //dd($data_depart);
        $list_subject = [];
        for($i = 0; $i < count($data_depart); $i++){
            $subject = new Requestapi('/api/v1/hiface/list?category=employee&department='.$data_depart[$i]->label.'&Management&page=1&size=100');
            try{
                $data_subject = json_decode($subject->methodGet());
            }catch(\GuzzleHttp\Exception\BadResponseException $e){
                $data_subject = null;
            }
            //dd($data_subject->data);
            $list_subject = array_merge($data_subject->data,$list_subject);
        }

        // dd($list_subject);
        return view('subject_permiss',['data' => $data_js, 'list_subject' => $list_subject]);
    }

    public function GetSJinGR(Request $request){
        $id = $request->input('id_gr');
        $response = new Requestapi('/api/v1/hiface/group/'.$id.'?page=1&size=8000');
        $data = json_decode($response->methodGet());
        return \response()->json($data->data->subjects);
    }

    public function SaveSubjectInGroup(Request $request){
        
        $sj_in_group = new Requestapi('/api/v1/hiface/group/'.$request->input('group').'?page=1&size=8000');
        $sj_in_group = json_decode($sj_in_group->methodGet());
        $sj_old_in_group = $sj_in_group->data->subjects;
        $sj_arr_id_old = [];
        for($i=0; $i < count($sj_old_in_group); $i++){
            $sj_arr_id_old[] = $sj_old_in_group[$i]->id;
        }
        $sj_old_in_group = $sj_arr_id_old;
        //dd($sj_old_in_group);
        $diff = array_intersect($sj_old_in_group,$request->input('subject'));
        // get subject delete in ajax
        $diff_old = array_diff($sj_old_in_group,$diff);
        // delete subject
        //dd($diff_old);

        if(!empty($diff_old)){
            $del = [
                'subject_ids' => array_values($diff_old),
            ];
            //dd($del);
            // foreach($diff_old as $dif){
            //     $resq = new Requestapi('/api/v1/hiface/list?category=employee&department='.$dif.'&page=1&size=1000');
            //     $resq = json_decode($resq->methodGet());
            //     foreach($resq->data as $dif_val){
            //         $arr_del_diff[] = $dif_val->id;
            //     }
            // }
            $req = new Requestapi('/api/v1/hiface/group/'.$request->input('group').'/delete');
            $return['del'] =  json_decode($req->getResponse($del));
        }
        
        // insert department to group 
        $diff_new = array_diff($request->input('subject'),$diff);
        //dd($diff_new);
        if(!empty($diff_new)){
            $insert = [
                'subject_ids' => array_values($diff_new),
            ];
            // foreach($diff_new as $new){
            //     $resq = new Requestapi('/api/v1/hiface/list?category=employee&department='.$new.'&page=1&size=1000');
            //     $resq = json_decode($resq->methodGet());
            //     foreach($resq->data as $dif_v){
            //         $arr_insert_diff[] = $dif_v->id;
            //     }
            // }
            $req = new Requestapi('/api/v1/hiface/group/'.$request->input('group').'/insert');
            $return['insert'] =  json_decode($req->getResponse($insert));
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

@extends('layout.main')

@section('main')
@include('layout.page-title', ['name' => 'Cấu hình hệ thống','tree' => 2])

<div class="row">
    <!-- Column -->
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="">
                    <div class="form-body">
                        <h3 class="card-title">Cấu hình hệ thống</h3>
                        <hr>
                        <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="control-label">Thermal ip</label>
                                <input type="text" class="form-control" id="thermal" value="@if($data != null)@foreach($data->thermal_info as $ther){{$ther->ip}},@endforeach @endif">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Hiface ip</label>
                                <input type="text" class="form-control" value="@if($data != null){{$data->hiface_info->ip}}@endif">
                            </div>

                            
                            <div class="form-group">
                                <button class="btn btn-success" id="submit">Lưu</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                <label for="" class="control-label">Username</label>
                                <input type="text" class="form-control" value="@if($data != null){{$data->hiface_info->username}}@endif">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Password</label>
                                <input type="password" class="form-control" value="@if($data != null){{$data->hiface_info->password}}@endif">
                            </div>
                        </div>
                    </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Column -->

</div>

@endsection


@section('script')
@include('layout.script')
    <script>
        $(function(){
            $('#thermal').tagsInput({
                'defaultText':'',
                'height':'44px',
                'width':'100%',
            });
            $('#submit').on('click',(e)=>{
                e.preventDefault();
                var inp = $('.form-group input');
                var data = {
                    'thermal_ip': inp.eq(0).val(),
                    'hiface_ip':inp.eq(2).val(),
                    'username':inp.eq(3).val(),
                    'password':inp.eq(4).val(),

                }
                console.log(data);
                $.ajax({
                    url: '{{route('set_config_post')}}',
                    method: 'post',
                    headers: {
                        'X-CSRF-Token': '{{csrf_token()}}' 
                    },
                    data : data
                }).done(result => {
                    console.log(result);
                    //setTimeout(function(){location.reload();},1000);

                    if(result.code == 0){
                        $.MessageBox({
                            buttonDone: "OK",
                            buttonFail : undefined,
                            top: "25%",
                            input: false,
                            message: "Cập nhật thành công",
                            queue: true,
                            speed: 200,
                        }).done(function(){
                            location.reload()
                        });
                        //setTimeout(location.reload(),5000);
                    }else{
                        $.MessageBox({
                            buttonDone: "OK",
                            buttonFail : undefined,
                            top: "25%",
                            input: false,
                            message: "Lỗi không xác định",
                            queue: true,
                            speed: 200,
                        });
                    }
                    //location.reload();
                });
            });
        });
        
    </script>
@endsection
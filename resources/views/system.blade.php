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
                                <label for="" class="control-label">Server ip</label>
                            <input type="text" class="form-control" value="{{$data->Server_ip}}">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Thermal ip</label>
                                <input type="text" class="form-control" value="{{$data->thermal_ip}}">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Hiface ip</label>
                                <input type="text" class="form-control" value="{{$data->hiface_ip}}">
                            </div>

                            
                            <div class="form-group">
                                <button class="btn btn-success" id="submit">Lưu</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                <label for="" class="control-label">Username</label>
                                <input type="text" class="form-control" value="{{$data->username}}">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Password</label>
                                <input type="text" class="form-control" value="{{$data->password}}">
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
            $('#submit').on('click',(e)=>{
                e.preventDefault();
                var inp = $('.form-group input');
                var data = {
                    'Server_ip': inp.eq(0).val(),
                    'thermal_ip': inp.eq(1).val(),
                    'hiface_ip':inp.eq(2).val(),
                    'username':inp.eq(3).val(),
                    'password':inp.eq(4).val(),

                }
                console.log(data);
                $.ajax({
                    url: '{{route('sys_conf_post')}}',
                    method: 'post',
                    headers: {
                        'X-CSRF-Token': '{{csrf_token()}}' 
                    },
                    data : data
                }).done(result => {
                    console.log(result);
                    if(result.status == 200){
                        setTimeout(location.reload(),5000);
                    }else{
                        $.notify({
                            icon: 'pe-7s-gift',
                            message: "Lỗi không xác định"
                            },{
                            type: 'danger',
                            timer: 9000
                        });
                    }
                    //location.reload();
                });
            });
        });
        
    </script>
@endsection
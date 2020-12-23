@extends('layout.main')

@section('main')
@include('layout.page-title',['name' => 'Thống kê dữ liệu nhân viên', 'tree' => 2])



<div class="row">
    <div class="col-12">
        <div class="card">
                 
            <div class="card-body">
                {{-- <div class="date_time"> --}}
                    <div class="row">
                        <div class="col-4">
                            <h4 class="card-title">Xuất báo cáo <button class="btn btn-success" id="reload">Refresh</button></h4>
                        </div>
                       <div class="col-3">
                           <input id="timerange" type="text" class="form-control" >
                       </div>
                       
                       <div class="col-4">
                           <div class="row">
                               <div>
                                   <select name="" class="form-control" id="thermal">
                                       @if($data != null)
                                        @foreach($data->thermalInfo as $v)
                                   <option value="{{$v->name}}"@if($v->name == 'Thermal_2') selected @endif>{{$v->name}}</option>
                                        @endforeach
                                        @endif
                                    </select> 
                               </div>
                                <div class="dropdown" style="position: absolute; right: 8px;">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Tổng hợp dữ liệu
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#" id="today_check">Quản lý ra vào hôm nay</a>
                                    <a class="dropdown-item" href="#" id="ago_check">Tổng hợp dữ liệu</a>
                                    </div>
                                </div>
                           </div>
                           {{-- <select name="" class="form-control" id="change_mode">
                                <option value="1" >Quản lý ra vào hôm nay</option>
                                <option value="2" selected>Tổng hợp dữ liệu</option>
                           </select> --}}
                           
                          
                       </div>
                       <div class="col-1">
                        <button class="btn btn-warning" id="querytime" ><i class="fas fa-search" ></i></button>
                    </div>
                    </div>
               {{-- </div> --}}
                <h6 class="card-subtitle">Xuất báo cáo ra định dạng Copy, CSV, Excel, PDF & Print</h6>
                <div class="table-responsive w-auto">
                    
                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Ảnh</th>   
                                <th>Tên</th>
                                <th>Phòng ban</th>
                                <th>Vị trí</th>
                                <th>Nhiệt độ</th>
                                <th>Mã nhân viên</th>
                                <th>Giờ vào</th>
                                <th>Giờ ra</th>
                                 
                            </tr>
                        </thead>
                        {{-- <tfoot>
                            <tr>
                                <th>Image</th>    
                                <th>Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Temperature</th>
                                <th>Employee id</th>
                                <th>Time checkin</th>
                                <th>Time checkout</th>
                                
                            </tr>
                        </tfoot> --}}
                        <tbody>
                            <tr id="loadtr">
                                <td colspan="8"><div class="loader">
                                    <div class="loader__figure"></div>
                                    <p class="loader__label">Loading</p>
                                </div></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                                <td style="display:none"></td>
                            </tr>
                         
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@include('layout.script')
<script>
    $(function () {
        // dropdown
        $('#today_check').on('click',()=>{

            $('#dropdownMenuButton').text('Quản lý ra vào hôm nay');
            $('#timerange').prop('disabled',true);
            $('#timerange').val('{{str_replace("-","/",$timenow)}} - {{str_replace("-","/",$timenow)}}');
                $('#querytime').prop('disabled',true);
                load.clear().draw();
                var tmp = `
                    <tr id="loadtr">
                        <td colspan="8"><div class="loader">
                            <div class="loader__figure"></div>
                        </div></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                    </tr>
                    `;
                $('#example23 tbody').append(tmp);
                $.ajax({
                url: '{{route('get_employee')}}',
                method: 'get'
                }).done(result => {
                    $('#loadtr').hide();
                    console.log('dropdown today');
                    $('#loadtr').hide();
                    result.forEach(item => {
                        load.row.add([`<img src="${item.image_path}" width="53" class="img-responsive avatar" >`,
                            item.name,
                            item.department,
                            item.position,
                            item.temperature,
                            item.employee_id,
                            item.time_checkin,
                            item.time_checkout
                        ]).draw();
                            
                    });                
                });
        });
        // dropdown 
        $('#ago_check').on('click',()=>{
            $('#dropdownMenuButton').text('Tổng hợp dữ liệu');
            $('#timerange').prop('disabled',false);
                $('#querytime').prop('disabled',false);
                load.clear().draw();
                var tmp = `
                    <tr id="loadtr">
                        <td colspan="8"><div class="loader">
                            <div class="loader__figure"></div>
                            <p class="loader__label">Loading</p>
                        </div></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                        <td style="display:none"></td>
                    </tr>
                    `;
                    $('#example23 tbody').append(tmp);
                $.ajax({
                    url: '{{route('post_employee_raw')}}',
                    method : 'post',
                        headers: {
                            'X-CSRF-Token': '{{csrf_token()}}' 
                    },
                    data: {
                        'timerange' : $('#timerange').val(),
                        'thermal' : $('#thermal').val()
                    }
                }).done(result => {
                    $('#loadtr').hide();
                    console.log('dropdown');
                    load.clear().draw();
                    result.forEach(item_query=>{
                        load.row.add([`<img src="${item_query.image_path}" width="53" class="img-responsive avatar" >`,
                            item_query.name,
                            item_query.department,
                            item_query.position,
                            item_query.temperature,
                            item_query.employee_id,
                            item_query.time_checkin,
                            item_query.time_checkout
                        ]).draw();
                    });
                });
        });

        // khởi tạo  timerange
        $('#timerange').daterangepicker({
            "maxDate": "{{$timenow}}",
            locale: {
                format: 'DD/MM/YYYY'
                }
        });
        $('#timerange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

       

        // load lại form
        $('#reload').on('click', e =>{
            e.preventDefault();
            location.reload();
        });


        $('#myTable').DataTable();
        var table = $('#example').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 2
            }],
            "order": [
                [2, 'asc']
            ],
            "displayLength": 25,
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;
                api.column(2, {
                    page: 'current'
                }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });

        //Order by the grouping
        $('#example tbody').on('click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });
        //responsive table
        $('#config-table').DataTable({
            responsive: true
        });
        
        
        
        var load = $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv','print', 'pdf','excel'
            ]
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
        $('.dt-buttons').children().attr('style','display:none');
        var select_export = `
        <select class="form-control" id="val-export" style="width:64%">
            <option>excel pro</option>
            <option>excel</option>
        </select>
        <button class="btn btn-warning" id="export-file" style="margin-bottom:4px"><i class="fas fa-download"></i></button>

        `;

        $('.dt-buttons').append(select_export);

        // export excel time range
        $('#export-file').on('click', e =>{
            e.preventDefault();
            let btn = $('#val-export option:selected').text();
            //console.log(btn);
            if(btn == 'excel'){
                var t_range = $('#timerange').val();
                t_range = t_range.split(' - ');
                $.ajax({
                    url : '{{route('pass_variable')}}',
                    method: 'post',
                    headers: {
                        'X-CSRF-Token': '{{csrf_token()}}',
                    },
                    data : {
                        'start' : t_range[0],
                        'end' : t_range[1],
                        'thermal' : $('#thermal').val()
                    }
                }).done(result => {
                    window.open(result.link,'_blank');
                });
                
            }else if(btn == 'excel pro'){
                var t_range = $('#timerange').val();
                t_range = t_range.split(' - ');
                $.ajax({
                    url : '{{route('pass_variable_pro')}}',
                    method: 'post',
                    headers: {
                        'X-CSRF-Token': '{{csrf_token()}}',
                    },
                    data : {
                        'start' : t_range[0],
                        'end' : t_range[1],
                        'thermal' : $('#thermal').val()
                    }
                }).done(result => {
                    window.open(result.link,'_blank');
                });
            }else{
                $('.buttons-'+btn).click();
            }

        });
        // lây dữ liệu khởi đầu
        startload();
        function startload(){
            $('#loadtr').show();
            $.ajax({
            url: '{{route('get_employee')}}',
            method: 'get'
            }).done(result => {
                $('#loadtr').hide();
                console.log(result);
                $('#loadtr').hide();
                result.forEach(item => {
                    load.row.add([`<img src="${item.image_path}" width="53" class="img-responsive avatar" >`,
                        item.name,
                        item.department,
                        item.position,
                        item.temperature,
                        item.employee_id,
                        item.time_checkin,
                        item.time_checkout
                    ]).draw();
                });                
            });
        }
        

        // lấy thông tin người dùng theo ngày

        $('#querytime').on('click', e =>{
            e.preventDefault();
            console.log('ádasd');
            load.clear().draw();
            var tmp = `
                <tr id="loadtr">
                    <td colspan="8"><div class="loader">
                        <div class="loader__figure"></div>
                        <p class="loader__label">Loading</p>
                    </div></td>
                    <td style="display:none"></td>
                    <td style="display:none"></td>
                    <td style="display:none"></td>
                    <td style="display:none"></td>
                    <td style="display:none"></td>
                    <td style="display:none"></td>
                    <td style="display:none"></td>
                </tr>
                `;
                $('#example23 tbody').append(tmp);
            $.ajax({
                url: '{{route('post_employee_raw')}}',
                method : 'post',
                headers: {
                    'X-CSRF-Token': '{{csrf_token()}}' 
               },
                data: {
                    'timerange' : $('#timerange').val(),
                    'thermal': $('#thermal').val()
                }
            }).done(result => {
                $('#loadtr').hide();
                console.log(result);
                load.clear().draw();
                var data_query;
                result.forEach(item_query=>{
                    load.row.add([`<img src="${item_query.image_path}" width="53" class="img-responsive avatar" >`,
                        item_query.name,
                        item_query.department,
                        item_query.position,
                        item_query.temperature,
                        item_query.employee_id,
                        item_query.time_checkin,
                        item_query.time_checkout
                    ]).draw();
                });
            });
        });


    });
        

</script>
@endsection
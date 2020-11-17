@extends('layout.main')

@section('main')
@include('layout.page-title',['name' => 'Quản lý nhân viên', 'tree' => 2])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Xuất báo cáo <button class="btn btn-success" id="reload">Refresh</button></h4>
                
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
        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });
        // responsive table
        $('#config-table').DataTable({
            responsive: true
        });
        var load =  $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv','print',
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    exportOptions: {
                        stripHtml: false
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'pdf',
                    customize: function (doc) {
                        if (doc) {
                            for (var i = 1; i < doc.content[1].table.body.length; i++) {
                    
                                var tmptext = doc.content[1].table.body[i][0].text;
                                console.log(tmptext);
                                tmptext = tmptext.substring(10, tmptext.indexOf("width=") - 2);
                                
                                doc.content[1].table.body[i][0] = {
                                    margin: [0, 0, 0, 12],
                                    alignment: 'center',
                                    image: tmptext,
                                    width: 60,
                                    height: 58
                                };
                            }
                        }
                    },
                    exportOptions: {
                        stripHtml: false
                    }
                }
            ]
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
        $('.dt-buttons').children().attr('style','display:none');
        var select_export = `
        <select class="form-control" id="val-export" style="width:64%">
            <option>copy</option>
            <option>csv</option>
            <option>print</option>
            <option>pdf</option>
            <option>excel</option>
        </select>
        <button class="btn btn-warning" id="export-file" style="margin-bottom:4px"><i class="fas fa-download"></i></button>
        `;

        $('.dt-buttons').append(select_export);

        $('#export-file').on('click', e =>{
            e.preventDefault();
            let btn = $('#val-export option:selected').text();
            //console.log(btn);
            if(btn == 'excel'){
                window.open('{{route('excel_report_today')}}','_blank');
            }else{
                $('.buttons-'+btn).click();
            }
            

        });
        $('#reload').on('click', e =>{
            e.preventDefault();
            window.location = window.location;
        });
        $.ajax({
            url: '{{route('get_employee')}}',
            method: 'get'
        }).done(result => {
            //console.log(result);
            $('#loadtr').hide();
            load.clear().draw();
            result.forEach(item => {
                // let temp = `
                //     <tr>
                //         <td><img src="${item.image_path}" width="53" class="img-responsive avatar" ></td>  
                //         <td>${item.name}</td>
                //         <td>${item.department}</td>
                //         <td>${item.position}</td>
                //         <td>${item.temperature}</td>
                //         <td>${item.employee_id}</td>
                //         <td>${item.time_checkin}</td>
                //         <td>${item.time_checkout}</td>
                          
                //     </tr>
                //     `;
                    // $('#example23 tbody').append(temp);
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


        

</script>
@endsection
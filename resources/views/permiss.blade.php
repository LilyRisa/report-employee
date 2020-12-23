@extends('layout.main')
@section('head')
<style>
    .selected-wrapper .non-selected-wrapper{
        width: 100%;
        display: flex;
        flex-wrap: wrap;
    }
    #listgroup tbody tr {
        cursor: pointer;
    }
    #listgroup tbody tr:hover{
        background-color: #437db7c9;
    }
    
    #listgroup tbody .active {
        background-color: #107ae4c9;
    }
</style>
@endsection

@section('main')
@include('layout.page-title', ['name' => 'Nhóm nhân viên','tree' => 2])

<div class="row">
    <!-- Column -->
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                            <table id="listgroup" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Group name</th>
                                        <th>Type</th>
                                        <th>NoP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($data != null)
                                    @foreach($data as $value)
                                    <tr id="listgr_{{$value->id}}" data-id="{{$value->id}}">
                                        <td>{{$value->name}}</td>
                                        <td>{{$value->subject_type}}</td>
                                        <td>{{$value->subject_count}}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                    </div>
                    <div class="col">
                        <select name="" multiple="multiple" id="group">
                            @if($data_depart != null)
                            @foreach($data_depart as $v)
                                <option value="{{$v->value}}">{{$v->label}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-success" id="submit">Save</button>
                </div>
                
            </div>
        </div>
    </div>
    <!-- Column -->

</div>

@endsection


@section('script')
@include('layout.script')
<script>
    $('#listgroup').DataTable({
        dom: 'Bfrtip',
    });
    $('.dt-buttons').hide();
    $('#group').multi();
    var url = window.location.href;
    var param = url.split('?');
    if (typeof param[1] !== 'undefined') {
        $.each($('#listgroup tbody tr'), function(i,v){
            if($(v).attr('id') === param[1]){
                $(v).attr('class','active');
            }
        });
    }

    // bắt sự kiện click column
    $('#listgroup tbody tr').on('click', function(){
        var id = $(this).attr('id')
        window.location.href = "?"+id;
    });
    if (typeof param[1] !== 'undefined') {
        var iid = param[1].split('_')[1];
        console.log(iid);
        $.ajax({
            url: '{{route('dp_in_gr')}}',
            method: 'post',
            headers: {
                'X-CSRF-Token': '{{csrf_token()}}',
            },
            data:{
                'id_gr':iid
            }
        }).done(result => {
            console.log(result);

            $('#group option:selected').removeAttr('selected');
            $('.non-selected-wrapper .item').attr('class','item');
            $('.selected-wrapper').html('');
            var option = $('#group option');
            var op_se = $('.non-selected-wrapper .item');
            $.each(option, (i,v) => {
                if($.inArray($(v).attr('value'), result) !== -1){
                    $(v).attr("selected", true);
                    
                }
            });
            $.each(op_se, (i,v) => {
                if($.inArray($(v).attr('data-value'), result) !== -1){
                    $(v).attr("class", 'item selected');
                    var node = v.cloneNode(true);
                    $('.selected-wrapper').append(node);
                }
            });
        });
    }
    $('#submit').on('click',function(){
        if (typeof param[1] !== 'undefined') {
            console.log(iid);
            console.log($('#group').val());
            $.ajax({
                url: '{{route('save_dp')}}',
                method: 'post',
                headers: {
                    'X-CSRF-Token': '{{csrf_token()}}',
                },
                data:{
                    'group':iid,
                    'department': $('#group').val()
                }
            }).done(result => {
                console.log(result);
                var tmp = '';
                if(typeof result.insert !== 'undefined'){
                    if(result.insert.code == 0){
                        tmp += 'thêm thành công<br/>';
                    }
                }
                if( typeof result.delete !== 'undefined'){
                    if(result.delete.code == 0){
                        tmp += 'xóa thành công';
                    }
                    
                }
                $.MessageBox({
                    buttonDone: "OK",
                    buttonFail : undefined,
                    top: "25%",
                    input: false,
                    message: "<b>Trạng thái</b><br/>"+tmp,
                    queue: true,
                    speed: 200,
                }).done(()=>{
                   location.reload();
                });
            });
        }else{
            $.MessageBox({
                buttonDone: "OK",
                buttonFail : undefined,
                top: "25%",
                input: false,
                message: "Bạn chưa chọn mục nào",
                queue: true,
                speed: 200,
            });
        }
    });
   
</script>
    <script>

    // var list_depart = [];

    // $('#item')
    // var iid;
    //    $('#listgroup').DataTable({
    //         dom: 'Bfrtip',
    //     });
    //     $('.dt-buttons').hide();
    //     geturl();
        
    //     function geturl(){
    //         const url = window.location.href; // lấy url
    //         var param = url.split('#');
    //         if (typeof param[1] !== 'undefined') {
    //             var column = $('#listgroup tbody tr');
    //             // ..console.log(column);
                
    //             $.each(column, function(i,v){
    //                 if($(v).attr('id') === param[1]){
    //                     console.log($(v).attr('id'));
    //                     iid = $(v).attr('id');
    //                     $(v).attr('class','active');
    //                     iid = iid.split('_');
    //                     if (typeof iid[1] !== 'undefined') {
    //                         iid = iid[1];
    //                     }else{
    //                         iid = null;
    //                     }
    //                 }
    //             });
    //         };
    //     }
        
        
        
    //     $.ajax({
    //             url: '{{route('dp_in_gr')}}',
    //             method: 'post',
    //             headers: {
    //                 'X-CSRF-Token': '{{csrf_token()}}',
    //             },
    //             data:{
    //                 'id_gr':iid
    //             }
    //         }).done(result => {
    //             $('#group option:selected').removeAttr('selected');
    //             $('.non-selected-wrapper .item').attr('class','item');
    //             $('.selected-wrapper').html('');
    //             console.log(result);
    //             var option = $('#group option');
    //             var op_se = $('.non-selected-wrapper .item');
    //             $.each(option, (i,v) => {
    //                 if($.inArray($(v).attr('value'), result) !== -1){
    //                     $(v).attr("selected", true);
                        
    //                 }
    //             });
    //             $.each(op_se, (i,v) => {
    //                 if($.inArray($(v).attr('data-value'), result) !== -1){
    //                     $(v).attr("class", 'item selected');
    //                     var node = v.cloneNode(true);
    //                     $('.selected-wrapper').append(node);
                        
    //                 }
    //             });
    //         });


    //     $('#group').multi();
    //     $('#listgroup tbody tr').on('click', function(){ // bắt sự kiện click column
        
    //         //geturl();
    //         const that = $(this);
    //         var id = that.attr('id');
    //         if (typeof (history.pushState) != "undefined") {
    //             var obj = {Page: '#'+id, Url: '#'+id};
    //             history.pushState(obj, obj.Page, obj.Url);
    //             $('#listgroup tbody tr').attr('class','')
    //            that.attr('class','active');
               
    //         } else {
    //             window.location.href = "#"+id;
    //         }
    //         const data_id = that.attr('data-id');
    //         // lấy data
    //         $.ajax({
    //             url: '{{route('dp_in_gr')}}',
    //             method: 'post',
    //             headers: {
    //                 'X-CSRF-Token': '{{csrf_token()}}',
    //             },
    //             data:{
    //                 'id_gr':data_id
    //             }
    //         }).done(result => {
    //         //     var option = $('#group option');
    //         //    // var op_se = $('.non-selected-wrapper .item');
    //         //     $.each(option, (i,v) => {
    //         //         if($.inArray($(v).attr('value'), result) !== -1){
    //         //             $(v).attr("selected", true);
                        
    //         //         }
    //         //     });

    //             $('#group option:selected').removeAttr('selected');
    //             $('.non-selected-wrapper .item').attr('class','item');
    //             $('.selected-wrapper').html('');
    //             //console.log(result);
    //             var option = $('#group option');
    //             var op_se = $('.non-selected-wrapper .item');
    //             $.each(option, (i,v) => {
    //                 if($.inArray($(v).attr('value'), result) !== -1){
    //                     $(v).attr("selected", true);
                        
    //                 }
    //             });
    //             $.each(op_se, (i,v) => {
    //                 if($.inArray($(v).attr('data-value'), result) !== -1){
    //                     $(v).attr("class", 'item selected');
    //                     var node = v.cloneNode(true);
    //                     $('.selected-wrapper').append(node);
                        
    //                 }
    //             });

                
    //         });


    //     });

    //     // event submit
    //     console.log(iid);
    //     $('#submit').on('click',function(){
            
    //         if(iid != null){
    //             console.log('=====');
    //             $.ajax({
    //             url: '{{route('save_dp')}}',
    //             method: 'post',
    //             headers: {
    //                 'X-CSRF-Token': '{{csrf_token()}}',
    //             },
    //             data:{
    //                 'group':iid,
    //                 'department': $('#group').val()
    //             }
    //         }).done(result => {
    //             console.log(result);
    //             var tmp;
    //             if(result.insert == '{"code":0}'){
    //                 tmp += 'lưu,';
    //             }
    //             if(result.del == '{"code":0}'){
    //                 tmp += 'xóa';
    //             }
    //             if(tmp != null){
    //                 alert('Đã'+tmp)
    //             }
    //             setInterval(function(){
    //                 location.reload();
    //             },3000);
    //         });
    //         }else{
    //             alert('bạn chưa chọn mục nào');
    //         }
            
    //     });
    </script>
@endsection
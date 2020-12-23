@extends('layout.main')
@section('head')
<link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
<style>
     a:hover{
        text-decoration: none !important;
    }
    .select-checkbox{
        width: 50%;
    }
    .select-checkbox option::before {
  content: "\2610";
  width: 1.3em;
  text-align: center;
  display: inline-block;
}
.select-checkbox option:checked::before {
  content: "\2611";
}

.select-checkbox-fa option::before {
  font-family: FontAwesome;
  content: "\f096";
  width: 1.3em;
  display: inline-block;
  margin-left: 2px;
}
.select-checkbox-fa option:checked::before {
  content: "\f046";
}
.bootstrap-select .btn:focus {
    outline: none !important;
}
.select-checkbox::-webkit-scrollbar {display:none;}
.select-checkbox::-moz-scrollbar {display:none;}
.select-checkbox::-o-scrollbar {display:none;}
.select-checkbox::-google-ms-scrollbar {display:none;}
.select-checkbox::-khtml-scrollbar {display:none;}
.child-check{
  margin-left: 15px;
  display: none;
}

.child-check.active{
  display: block;
}

</style>
@endsection
@section('main')
@include('layout.page-title', ['name' => 'Đồng bộ dữ liệu','tree' => 2])

<div class="row">
    <!-- Column -->
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="">
                    <div class="form-body">
                        <h3 class="card-title">Đồng bộ dữ liệu</h3>
                        <hr>
                        <div class="row p-t-20">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{-- <label>Thermal</label>
                                @foreach($data as $key => $value)
                                
                                <div class="group-checkbox">
                                    <input type="checkbox" value="{{$key}}" class="inpcheckbox">
                                    <label for="{{$key}}"> {{$key}}</label><br>
                                    
                                    <select multiple="" class="form-control select-checkbox" size="5" disabled>
                                    @if($value != null)
                                        @foreach($value as $v)
                                            <option value="{{$v->id}}">{{$v->name}}</option> 
                                        @endforeach
                                    @endif
                                    </select>
                                </div>
                                @endforeach --}}
                                @foreach($data as $key => $value)
                                <?php //dd($value); dd($key);?>
                                <div class="parent-check">
                                    <input type="checkbox" value="{{$key}}"><label> {{$key}}</label>
                                    @if($value != null)
                                    @foreach($value as $v)
                                    <div class="child-check">
                                      <input type="checkbox" value="{{$v->id}}"><label> {{$v->name}}</label>
                                    </div>
                                    @endforeach
                                    @endif
                                 </div>
                                 @endforeach
                            </div>
                            <div class="form-group">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                                    aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:1%">
                                      1%
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success" id="submit">Sync</button>
                            </div>
                            
                            {{-- <div class="form-group">
                                <label for="" class="control-label">Server ip</label>
                            <input type="text" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Thermal ip</label>
                                <input type="text" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Hiface ip</label>
                                <input type="text" class="form-control" >
                            </div>
                            <div class="form-group">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                            </div>
                            
                            <div class="form-group">
                                <button class="btn btn-success" id="submit">Lưu</button>
                            </div> --}}
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
            $('.progress').hide();
            var checks = document.querySelectorAll("input[type=checkbox]");

for(var i = 0; i < checks.length; i++){
  checks[i].addEventListener( 'change', function() {
    if(this.checked) {
       showChildrenChecks(this);
    } else {
       hideChildrenChecks(this)
    }
  });
}

function showChildrenChecks(elm) {
   var pN = elm.parentNode;
   var childCheks = pN.children;
   
  for(var i = 0; i < childCheks.length; i++){
      if(hasClass(childCheks[i], 'child-check')){
	      childCheks[i].classList.add("active");      
      }
  }
   
}

function hideChildrenChecks(elm) {
   var pN = elm.parentNode;
   var childCheks = pN.children;
   
  for(var i = 0; i < childCheks.length; i++){
      if(hasClass(childCheks[i], 'child-check')){
	      childCheks[i].classList.remove("active");      
      }
  }
   
}

function hasClass(elem, className) {
    return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
}
// js check box bên trên 

            // var data_sync = [];
            // $('.select-checkbox').hide();
            // $('.group-checkbox').on('click',function(e){
            //     //console.log('sadasd');
            //     var that = $(this);
            //     that.children('.inpcheckbox').click();
            //     e.stopPropagation();
            //     if(that.children('.inpcheckbox').is(':checked')){
            //        let chil = that.children('.select-checkbox');
            //        chil.show();
            //        //chil.children('option').prop("selected",true);
            //     }else{
            //         let chil = that.children('.select-checkbox');
            //         chil.hide();
            //         //chil.children('option').prop("selected",false);
            //     }
            // });



            // submit 
            $('#submit').on('click',function(e){
                e.preventDefault();
                var sync = [];
                // for(let i=0; i< $('.inpcheckbox').length; i++){
                //     //console.log($('.inpcheckbox').eq(i));
                //     if($('.inpcheckbox').eq(i).is(':checked')){
                //         sync.push($('.inpcheckbox').eq(i).val());
                //     }
                // }
                
                for(let i=0; i < $('.parent-check').length; i++){
                    var chill_inp = [];
                    var obj_inp = {};
                    //sync.push($('.parent-check').eq(i).children('input').val());
                    if($('.parent-check').eq(i).children('input').is(":checked")){
                        obj_inp.thermal_name = $('.parent-check').eq(i).children('input').val();
                        for(let k=0; k < $('.parent-check').eq(i).children('.child-check').length;k++){
                            if($('.parent-check').eq(i).children('.child-check').eq(k).children('input').is(":checked")){
                                chill_inp.push($('.parent-check').eq(i).children('.child-check').eq(k).children('input').val());
                            }
                            
                        }
                        obj_inp.libs = chill_inp;

                        sync.push(obj_inp);
                    }
                    
                }
                //$('.parent-check checkbox:nth-child(2)').val();
                console.log(sync);
                var ob_sync = {...sync};
                console.log(ob_sync);
                $.ajax({
                    xhr: function()
                        {
                            var xhr = new window.XMLHttpRequest();
                            //Tiến trình tải lên
                            xhr.upload.addEventListener("progress", function(evt){
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                //Làm điều gì đó với tiến trình tải lên
                                $('.progress').show();
                                  
                                var k = 0; 
                                (function myLoop(i) {
                                    setTimeout(function() {
                                        $('.progress-bar').attr('style','width:'+k+'%') ;
                                        $('.progress-bar').text(k+'%') ;
                                        k++  //  your code here                
                                        if (--i) myLoop(i);   //  decrement i and call myLoop again if i > 0
                                    }, 20)
                                    })(50); 
                                console.log(percentComplete);
                            }
                            }, false);
                            //Tiến độ tải xuống
                            xhr.addEventListener("progress", function(evt){
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                var k = 50;
                                (function myLoop(i) {
                                    setTimeout(function() {
                                        $('.progress-bar').text(k+'%');
                                        $('.progress-bar').attr('style','width:'+k+'%');
                                        k++  //  your code here            
                                        if (--i) myLoop(i);   //  decrement i and call myLoop again if i > 0
                                    }, 10);
                                     
                                    })(51); 
                                console.log(percentComplete);
                            }
                            }, false);
                            return xhr;
                        },
                    url: '{{route('post_sync')}}',
                    method: 'post',
                    header:{
                        'X-CSRF-TOKEN': '{{csrf_token()}}' 
                    },
                    data: {
                        'request': ob_sync,
                        '_token': '{{csrf_token()}}'
                    },
                }).done((result) => {
                    console.log(result);
                    $.MessageBox({
                            buttonDone: "OK",
                            buttonFail : undefined,
                            top: "25%",
                            input: false,
                            message: "Đồng bộ thành công",
                            queue: true,
                            speed: 200,
                        });
                });
            });
            $('.group-checkbox').children('select').on('click',function(e){
                e.stopPropagation();
            });

            $('.select-checkbox option').mousedown(function(e) {
                e.preventDefault();
                var originalScrollTop = $(this).parent().scrollTop();
                console.log(originalScrollTop);
                $(this).prop('selected', $(this).prop('selected') ? false : true);
                var self = this;
                $(this).parent().focus();
                setTimeout(function() {
                    $(self).parent().scrollTop(originalScrollTop);
                }, 0);
                
                return false;
            });
        });
        
    </script>
@endsection
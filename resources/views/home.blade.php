@extends('layout.main')

@section('main')
@include('layout.page-title', ['name' => 'Dashboard','tree' => 1])

<div class="row">
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
            <a href="{{route('employee_raw')}}">                
                    <div class="d-flex flex-row">
                        <div class="round align-self-center round-success"><i class="ti-user"></i></div>
                        <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0" id="count_today">{{$count_employee_today}}</h3>
                            <h5 class="text-muted m-b-0">Nhân viên hôm nay</h5></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round align-self-center round-info"><i class="ti-wallet"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0" id="remaining"></h3>
                        <h5 class="text-muted m-b-0">Nhân viên trong văn phòng</h5></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round align-self-center round-danger"><i class="ti-calendar"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0">56%</h3>
                        <h5 class="text-muted m-b-0">Today's Profit</h5></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round align-self-center round-success"><i class="ti-settings"></i></div>
                    <div class="m-l-10 align-self-center">
                        <h3 class="m-b-0">56%</h3>
                        <h5 class="text-muted m-b-0">New Leads</h5></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
</div>

{{-- <div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title ">Leads by Source</h5>
                <div id="morris-donut-chart" class="ecomm-donute" style="height: 317px;"></div>
                <ul class="list-inline m-t-30 text-center">
                    <li class="p-r-20">
                        <h5 class="text-muted"><i class="fa fa-circle" style="color: #fb9678;"></i> Ads</h5>
                        <h4 class="m-b-0">8500</h4>
                    </li>
                    <li class="p-r-20">
                        <h5 class="text-muted"><i class="fa fa-circle" style="color: #01c0c8;"></i> Tredshow</h5>
                        <h4 class="m-b-0">3630</h4>
                    </li>
                    <li>
                        <h5 class="text-muted"> <i class="fa fa-circle" style="color: #4F5467;"></i> Web</h5>
                        <h4 class="m-b-0">4870</h4>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Column -->
    <div class="col-lg-8 col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Top Products sales</h5>
                <ul class="list-inline text-center">
                    <li>
                        <h5><i class="fa fa-circle m-r-5" style="color: #00bfc7;"></i>iMac</h5>
                    </li>
                    <li>
                        <h5><i class="fa fa-circle m-r-5" style="color: #b4becb;"></i>iPhone</h5>
                    </li>
                </ul>
                <div id="morris-area-chart2" style="height: 370px;"></div>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@section('script')
    @include('layout.script')
<script>                                  
    (function(){
        setInterval(function(){
        $.ajax({
            url: '{{route('count_today')}}',
            method : 'get'
        }).done(result => {
            $('#count_today').text(result.count_today).fadeIn();
            $('#remaining').text(result.number_of_employee).fadeIn();
        });      
    },10000);
    })();
</script>

@endsection
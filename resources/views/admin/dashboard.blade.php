@extends('layouts.admin_app')
@section('content')
       

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
                        
        <div class="row m-t-25">
            <div class="col-sm-6 col-lg-3">
                                <div class="overview-item overview-item--c1">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                            <div class="icon">
                                                <i class="zmdi zmdi-account-o"></i>
                                            </div>
                                            <br>
                                            <div class="text">
                                                <h2>10368</h2>
                                                <span>Members</span>
                                            </div>
                                        </div>
                                        <div class="overview-chart"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                            <canvas id="widgetChart2" height="130" style="display: block;" width="177" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                                <div class="overview-item overview-item--c2">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                            <div class="icon">
                                                <i class="zmdi zmdi-shopping-cart"></i>
                                            </div>
                                            <br>
                                            <div class="text">
                                                <h2>388,688</h2>
                                                <span>Sale Receipt</span>
                                            </div>
                                        </div>
                                        <div class="overview-chart"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                            <canvas id="widgetChart" height="115" width="177" class="chartjs-render-monitor" style="display: block; width: 177px; height: 115px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
        </div>

    
    </div>
  </div>
</div>



@endsection

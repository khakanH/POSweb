                <h3>Dashboard</h3>        
                <hr>
                <br>
                <div class="row">
                <div class="col-md-8">    
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Basic Info
                            </strong>
                        </div>
                        <div class="card-body">
                                

                               <!--  <div class="overview-item overview-item--c1" style="margin: 0px 0px 10px 0px;">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                            
                                        </div>
                                    
                                    </div>
                                </div>

                                <div class="overview-item overview-item--c2" style="margin: 0px 0px 10px 0px;">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                           
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="overview-item overview-item--c3" style="margin: 0px 0px 10px 0px;">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                           
                                        </div>
                                    </div>
                                </div>

                                <div class="overview-item overview-item--c4" style="margin: 0px 0px 10px 0px;">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                           
                                        </div>
                                    </div>
                                </div> -->



                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                     <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Date Time</strong>
                        </div>
                        
                        <div class="card-body" style="padding: 10px 0px 10px 0px;">
                       
                            <center>
                            <div onclick="ChangeClockType('2')" id="small_clock"  class="animated fadeIn" style="display: none; width: 270px; font-size: 18px;background-image: -webkit-linear-gradient(90deg, #3f5efb 0%, #fc466b 100%); color:white; border-radius: 25px; cursor: pointer;  text-shadow: 0 0 1px, 0 0 5px white;">
                            <br>
                            {{ date('D F d, Y') }}
                            <br>
                            <span id="live_time"></span>
                            <br>
                            <br>
                            <p>something here</p>
                            <br>
                            <br>
                            </div>
                            </center>

                        <center><canvas onclick="ChangeClockType('1')"  class="animated fadeIn" id="big_clock" width="500" height="500" style=" width: 240px; height: 246px; border-radius: 160px; cursor: pointer;" ></canvas></center>
                        



                        </div>
                    </div>            
                </div>
                </div>
                        

                <div class="row">
                <div class="col-md-12">
                                <div class="au-card m-b-40">
                                    <div class="au-card-inner"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                        <h3 class="title-2 m-b-40">Single Bar Chart</h3>
                                        <canvas id="singelBarChart" height="200px"  class="chartjs-render-monitor" style="display: block; height: 200px;"></canvas>
                                    </div>
                                </div>
                </div>
                </div>

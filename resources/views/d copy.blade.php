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


<script type="text/javascript">





    var canvas = document.getElementById("big_clock");
    var ctx = canvas.getContext("2d");

    ctx.strokeStyle = 'white';
    ctx.lineWidth = 20;
    ctx.shadowBlur= 20;
    ctx.shadowColor = 'white'

    function degToRad(degree){
      var factor = Math.PI/180;
      return degree*factor;
    }

    function renderTime(){
      var now = new Date();
      var today = now.toDateString();
      var time = now.toLocaleTimeString();
      var hrs = now.getHours();
      var min = now.getMinutes();
      var sec = now.getSeconds();
      var mil = now.getMilliseconds();
      var smoothsec = sec+(mil/1000);
      var smoothmin = min+(smoothsec/60);

      //Background
      gradient = ctx.createLinearGradient(100,50,500,0);
      gradient.addColorStop(0, "#fc466b");
      gradient.addColorStop(1, "#3f5efb");
      ctx.fillStyle = gradient;
      //ctx.fillStyle = 'rgba(00 ,00 , 00, 1)';
      ctx.fillRect(0, 0, 500, 500);
      //Hours
      ctx.beginPath();
      ctx.arc(250,250,200, degToRad(270), degToRad((hrs*30)-90));
      ctx.stroke();
      //Minutes
      ctx.beginPath();
      ctx.arc(250,250,170, degToRad(270), degToRad((smoothmin*6)-90));
      ctx.stroke();
      //Seconds
      ctx.beginPath();
      ctx.arc(250,250,140, degToRad(270), degToRad((smoothsec*6)-90));
      ctx.stroke();
      //Date
      ctx.font = "30px sans-serif";
      ctx.fillStyle = 'rgba(255, 255, 255, 1)'
      ctx.fillText(today, 135, 240);
      //Time
      ctx.font = "40px sans-serif";
      ctx.fillStyle = 'rgba(255, 255, 255, 1)';
      ctx.fillText(time, 135, 290);


    }
    setInterval(renderTime, 40);

    function ChangeClockType(type)
    {
        if (type == '1') 
        {
            document.getElementById('big_clock').style.display='none';
            document.getElementById('small_clock').style.display='block';
            startTime();
        }
        else
        {   
            document.getElementById('big_clock').style.display='block';
            document.getElementById('small_clock').style.display='none';
        }
    }

    function startTime() {
      var today = new Date();
      var h = today.getHours();
      var m = today.getMinutes();
      var s = today.getSeconds();

      if (h > 12) 
      {
        am_pm = "pm";
      }
      else
      {
        am_pm = "am";
      }

      h=  checkHour(h);
      m = checkTime(m);
      s = checkTime(s);
      document.getElementById('live_time').innerHTML =
      h + ":" + m + ":" + s + " " + am_pm;
      var t = setTimeout(startTime, 500);
    }
    function checkTime(i) {
      if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
      return i;
    }
    function checkHour(i) {
      if (i > 12) {i = i-12};  // add zero in front of numbers < 10
      return i;
    }







</script>
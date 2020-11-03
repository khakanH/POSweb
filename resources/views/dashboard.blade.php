@extends('layouts.app')
@section('content')

    
  
     <div class="page-content--bgf7">

            <!-- STATISTIC-->
            <section class="statistic statistic2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="statistic__item statistic__item--green">
                                <h2 class="number">10,368</h2>
                                <span class="desc">members online</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-account-o"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="statistic__item statistic__item--orange">
                                <h2 class="number">388,688</h2>
                                <span class="desc">items sold</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="statistic__item statistic__item--blue">
                                <h2 class="number">1,086</h2>
                                <span class="desc">this week</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-calendar-note"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="statistic__item statistic__item--red">
                                <h2 class="number">$1,060,386</h2>
                                <span class="desc">total earnings</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-money"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END STATISTIC-->

            <!-- STATISTIC CHART-->
            <section class="statistic-chart">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="title-5 m-b-35">statistics</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- CHART-->
                            <div class="statistic-chart-1">
                                <h3 class="title-3 m-b-30">chart</h3>
                                <div class="chart-wrap">
                                    <canvas id="widgetChart5"></canvas>
                                </div>
                                <div class="statistic-chart-1-note">
                                    <span class="big">10,368</span>
                                    <span>/ 16220 items sold</span>
                                </div>
                            </div>
                            <!-- END CHART-->
                        </div>
                       
                        <div class="col-lg-4">
                            <!-- CHART PERCENT-->
                            <div class="chart-percent-2">
                                <h3 class="title-3 m-b-30">chart by %</h3>
                                <div class="chart-wrap">
                                    <canvas id="percent-chart2"></canvas>
                                    <div id="chartjs-tooltip">
                                        <table></table>
                                    </div>
                                </div>
                                <div class="chart-info">
                                    <div class="chart-note">
                                        <span class="dot dot--blue"></span>
                                        <span>products</span>
                                    </div>
                                    <div class="chart-note">
                                        <span class="dot dot--red"></span>
                                        <span>Services</span>
                                    </div>
                                </div>
                            </div>
                            <!-- END CHART PERCENT-->
                        </div>
                    </div>
                </div>
            </section>
            <!-- END STATISTIC CHART-->

           

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

  
@endsection

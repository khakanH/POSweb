@extends('layouts.admin_app')
@section('content')
       

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
                        
   <div class="row"> 

          <div class="col-lg-8" style="padding: 35px 15px 0px 25px;" id="notifications-div">

            @if(count($notifications) == 0)
            <br>
            <br>
            <center><span class="fa fa-quote-left" style="position: absolute;
    margin: 3px 0px 0px -20px;"></span><i class="tx-26">{{$quote}}</i><span class="fa fa-quote-right" style="position: absolute;
    margin: 3px 0px 0px 10px;"></span></center>
            @endif

            @foreach($notifications as $key)
            <?php $alert_color = ($key['type'] == 1)? "primary" :(($key['type'] == 2)? "warning" : (($key['type'] == 3)?"danger":"secondary")); ?>
            <div class="sufee-alert alert with-close alert-{{$alert_color}} alert-dismissible fade show">
                  {{$key['title']}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick='MarkNotificationRead("{{$key['id']}}")'>
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            @endforeach 
         
          </div>
          <div class="col-lg-4">
                            <center>
                            <div onclick="ChangeClockType('2')" id="small_clock"  class="animated fadeIn" style="display: none; width: 270px; height: 246px; font-size: 18px;background-image: -webkit-linear-gradient(90deg, #3f5efb 0%, #fc466b 100%); color:white; border-radius: 25px; cursor: pointer;  text-shadow: 0 0 1px, 0 0 5px white; padding: 70px 10px 10px 10px;">
                            <br>
                            {{ date('D F d, Y') }}
                            <br>
                            <span id="live_time"></span>
                           
                            </div>
                            </center>

                        <center><canvas onclick="ChangeClockType('1')"  class="animated fadeIn" id="big_clock" width="500" height="500" style=" width: 240px; height: 246px; border-radius: 160px; cursor: pointer;" ></canvas></center>
                        



          </div>

        </div>



        <div class="row m-t-25">
            <div class="col-lg-6">
                                <div class="overview-item overview-item--c2">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                            <div class="icon">
                                                <i class="zmdi zmdi-account-o"></i>
                                            </div>
                                            <br>
                                            <div class="text">
                                                <h2>{{array_sum($member_count)}}</h2>
                                                <span> Members </span>
                                            </div>
                                        </div>
                                        <div class="overview-chart"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                            <canvas id="members_chart" height="130" style="display: block;" width="177" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                </div>
            </div>
            <div class="col-lg-6">
                                <div class="overview-item overview-item--c3">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                            <div class="icon">
                                                <i class="zmdi zmdi-store"></i>
                                            </div>
                                            <br>
                                            <div class="text">
                                                <h2>{{array_sum($company_count)}}</h2>
                                                <span>Companies</span>
                                            </div>
                                        </div>
                                        <div class="overview-chart"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                            <canvas id="company_chart" height="130" style="display: block;" width="177" class="chartjs-render-monitor"></canvas>
                                        </div>
                                    </div>
                                </div>
            </div>
           
        </div>

    
    </div>
  </div>
</div>

<script src="{{ asset('vendor/chartjs/Chart.bundle.min.js') }}"></script>

<script type="text/javascript">


    function NewNotification()
    {
      $.ajax({
            type: "GET",
            url: "{{ env('APP_URL')}}admin/new-notifications-admin",
            success: function(data) {
            
              $('#notifications-div').html(data);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
          });
    }


    document.addEventListener("DOMContentLoaded", function(event) { 
     setInterval(NewNotification,3000);  
    });

try
{
    
    var member = <?php echo '["' . implode('", "', $member_count) . '"]' ?>;

    var ctx = document.getElementById("members_chart");
    if (ctx) {
      ctx.height = 130;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul','Aug','Sept','Oct','Nov','Dec'],
          type: 'line',
          datasets: [{
            data: member,
            label: 'Members',
            backgroundColor: 'transparent',
            borderColor: 'rgba(255,255,255,.55)',
          },]
        },
        options: {

          maintainAspectRatio: false,
          legend: {
            display: false
          },
          responsive: true,
          tooltips: {
            mode: 'index',
            titleFontSize: 12,
            titleFontColor: '#000',
            bodyFontColor: '#000',
            backgroundColor: '#fff',
            titleFontFamily: 'Montserrat',
            bodyFontFamily: 'Montserrat',
            cornerRadius: 3,
            intersect: false,
          },
          scales: {
            xAxes: [{
              gridLines: {
                color: 'transparent',
                zeroLineColor: 'transparent'
              },
              ticks: {
                fontSize: 2,
                fontColor: 'transparent'
              }
            }],
            yAxes: [{
              display: false,
              ticks: {
                display: false,
              }
            }]
          },
          title: {
            display: false,
          },
          elements: {
            line: {
              tension: 0.00001,
              borderWidth: 1
            },
            point: {
              radius: 4,
              hitRadius: 10,
              hoverRadius: 4
            }
          }
        }
      });
    }

// ---------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------



    var company = <?php echo '["' . implode('", "', $company_count) . '"]' ?>;

    var ctx = document.getElementById("company_chart");
    if (ctx) {
      ctx.height = 130;
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul','Aug','Sept','Oct','Nov','Dec'],
          type: 'line',
          datasets: [{
            data: company,
            label: 'Companies',
            backgroundColor: 'transparent',
            borderColor: 'rgba(255,255,255,.55)',
          },]
        },
        options: {

          maintainAspectRatio: false,
          legend: {
            display: false
          },
          responsive: true,
          tooltips: {
            mode: 'index',
            titleFontSize: 12,
            titleFontColor: '#000',
            bodyFontColor: '#000',
            backgroundColor: '#fff',
            titleFontFamily: 'Montserrat',
            bodyFontFamily: 'Montserrat',
            cornerRadius: 3,
            intersect: false,
          },
          scales: {
            xAxes: [{
              gridLines: {
                color: 'transparent',
                zeroLineColor: 'transparent'
              },
              ticks: {
                fontSize: 2,
                fontColor: 'transparent'
              }
            }],
            yAxes: [{
              display: false,
              ticks: {
                display: false,
              }
            }]
          },
          title: {
            display: false,
          },
          elements: {
            line: {
              tension: 0.00001,
              borderWidth: 1
            },
            point: {
              radius: 4,
              hitRadius: 10,
              hoverRadius: 4
            }
          }
        }
      });
    }





} 
catch (error) 
{
    console.log(error);
}

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



    function MarkNotificationRead(id)
    {
      $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/mark-notification-read-admin/"+id,
        success: function(data) {
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }

</script>

@endsection

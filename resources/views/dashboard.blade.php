@extends('layouts.app')
@section('content')

    
  
            <div class="tab-content">
                <!-- dashboard tab starts here -->
                <div class="tab-pane active" id="dashboard" role="tabpanel">
                    <div class="heading-calender">
                        <h4>Dashboard</h4>
                        <h4 class="calender">Calender</h4>
                    </div>
                    <div class="table-div ">
                       <div class="revenue-head"> <h3 >Revenue</h3></div>
                        <table class="table table-bordered table-style" >
                            <tbody>
                              <tr>
                                <td>
                                    <p class="rev-head">Revenue today</p> 
                                    <p class="rev-value">PKR 90,000</p>
                                </td>
                                <td  >
                                    <p class="rev-head">Revenue Yesterday</p> 
                                    <p class="rev-value">PKR 90,000</p>
                                </td>
                                <td  >
                                    <p class="rev-head">Revenue this month</p> 
                                    <p class="rev-value">PKR 90,000</p>
                                </td>
                              </tr>
                              <td  >
                                  <p class="rev-head">Total sales today</p> 
                                  <p class="rev-value">PKR {{$total_sale_today}}</p>
                              </td>
                              <td  >
                                  <p class="rev-head">Total sales Yesterday</p> 
                                  <p class="rev-value">PKR {{$total_sale_yesterday}}</p>
                              </td>
                              <td  >
                                  <p class="rev-head">Total sales this month</p> 
                                  <p class="rev-value">PKR {{$total_sale_month}}</p>
                              </td>
                            </tbody>
                          </table>
                          <div class="graph">
                            <div class="col-lg-12">
                                <div class="au-card m-b-30">
                                    <div class="au-card-inner"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                        <h3 class="title-2 m-b-40">Monthly Stats - {{date('Y')}}</h3>
                                        <canvas id="team-chart" height="185" width="370" class="chartjs-render-monitor" style="display: block; width: 370px; height: 185px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="top-products">
                            <br>
                            <center><div class="top-prod-head"> <h4>Top Products this year</h4></div></center>
                            <br>
                        </div>
                        <div class="top-prod-boxes row">
                            
                            <div class="col-lg-1"></div>
                             <div class="col-lg-10">
                              <div class="m-b-30">
                                    <div class="au-card-inner"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                        <canvas id="pieChart" height="246" width="370" class="chartjs-render-monitor" style="display: block; width: 370px; height: 246px;"></canvas>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-lg-1"></div>

                        </div>
                    </div>
                </div>
                <!-- dashboard tab ends here -->
                <div class="tab-pane" id="pos" role="tabpanel">pos</div>
                <div class="tab-pane" id="categories" role="tabpanel">categories</div>
                <div class="tab-pane" id="inventory" role="tabpanel">Inventory</div>
                <div class="tab-pane" id="sales" role="tabpanel">sales</div>
                <div class="tab-pane" id="customers" role="tabpanel">customers</div>
                <div class="tab-pane" id="users" role="tabpanel">users</div>
                <div class="tab-pane" id="settings" role="tabpanel">settings</div>
                <div class="tab-pane" id="profile" role="tabpanel">profile</div>
                <div class="tab-pane" id="logout" role="tabpanel">logout</div>
            </div>











    <script src="{{ asset('vendor/chartjs/Chart.bundle.min.js') }}"></script>
                      
        <script type="text/javascript">
            
    try{
          var sale = <?php echo '["' . implode('", "', $monthly_sale) . '"]' ?>;

                  var ctx = document.getElementById("team-chart");
                  if (ctx) {
                    ctx.height = 150;
                    var myChart = new Chart(ctx, {
                      type: 'line',
                      data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
                        type: 'line',
                        defaultFontFamily: 'Poppins',
                        datasets: [{
                          // data: [25,23,16,17,18,12,15,15,19,17,15,14],
                          data: sale,
                          label: "Sale",
                          backgroundColor: 'transparent',
                          // backgroundColor: 'rgba(96,86,165,0.2)',
                          borderColor: 'rgba(58,62,151,0.6)',
                          borderWidth: 2,
                          pointStyle: 'circle',
                          pointRadius: 4,
                          pointBorderColor: 'black',
                          pointBackgroundColor: '#ffeb3b',
                        },]
                      },
                      options: {
                        responsive: true,
                        tooltips: {
                          mode: 'index',
                          titleFontSize: 12,
                          titleFontColor: '#000',
                          bodyFontColor: '#000',
                          backgroundColor: '#fff',
                          titleFontFamily: 'Poppins',
                          bodyFontFamily: 'Poppins',
                          cornerRadius: 3,
                          intersect: false,
                        },
                        legend: {
                          display: false,
                          position: 'top',
                          labels: {
                            usePointStyle: true,
                            fontFamily: 'Poppins',
                          },


                        },
                        scales: {
                          xAxes: [{
                            display: true,
                            gridLines: {
                              display: false,
                              drawBorder: false
                            },
                            scaleLabel: {
                              display: false,
                              labelString: 'Month'
                            },
                            ticks: {
                              fontFamily: "Poppins"
                            }
                          }],
                          yAxes: [{
                            display: true,
                            gridLines: {
                              display: true,
                              drawBorder: true
                            },
                            scaleLabel: {
                              display: true,
                              labelString: 'Sales',
                              fontFamily: "Poppins"
                            },
                            ticks: {
                              fontFamily: "Poppins",
                              beginAtZero: true,  
                              suggestedMin: 0,
                              suggestedMax: "<?php echo array_sum($monthly_sale)+(array_sum($monthly_sale)/2); ?>",
                            }
                          }]
                        },
                        title: {
                          display: false,
                        }
                      }
                    });
                  }


  } catch (error) {
    console.log(error);
  }


        try {

          var p_name = <?php echo '["' . implode('", "', $top_prod_name) . '"]' ?>;
          var p_count = <?php echo '["' . implode('", "', $top_prod_count) . '"]' ?>;


    //pie chart
    var ctx = document.getElementById("pieChart");
    if (ctx) {
      ctx.height = 150;
      var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
          datasets: [{
            data: p_count,
            backgroundColor: [
              "rgba(58,62,151,1.0)",
              "rgba(58,62,151,0.8)",
              "rgba(58,62,151,0.6)",
              "rgba(58,62,151,0.4)",
              "rgba(58,62,151,0.2)",
            ],
            hoverBackgroundColor: [
              "rgba(58,62,151,1.0)",
              "rgba(58,62,151,0.8)",
              "rgba(58,62,151,0.6)",
              "rgba(58,62,151,0.4)",
              "rgba(58,62,151,0.2)",
            ]

          }],
          labels: p_name
        },
        options: {
          legend: {
            position: 'top',
            labels: {
              fontFamily: 'Poppins'
            }

          },
          responsive: true
        }
      });
    }


  } catch (error) {
    console.log(error);
  }



        </script>
                        
                      
                       



  
@endsection

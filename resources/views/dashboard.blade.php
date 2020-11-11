@extends('layouts.app')
@section('content')

    
  
     <div class="page-content--bgf7">

            <!-- STATISTIC-->
            <section class="statistic statistic2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="statistic__item statistic__item--green">
                                <h2 class="number">{{$customer_count}}</h2>
                                <span class="desc">Customers</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-account-o"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="statistic__item statistic__item--orange">
                                <h2 class="number">{{$total_item}}</h2>
                                <span class="desc">items sold</span>
                                <div class="icon">
                                    <i class="zmdi zmdi-shopping-cart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="statistic__item statistic__item--blue">
                                <h2 class="number">{{$product_count}}</h2>
                                <span class="desc">Products in {{$category_count}} Categories</span>
                                <div class="icon">
                                    <i class="fa fa-th-large"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="statistic__item statistic__item--red">
                                <h2 class="number">{{number_format($total_sale,2)}}</h2>
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
                                <div class="au-card m-b-30">
                                    <div class="au-card-inner"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                        <h3 class="title-2 m-b-40">Monthly Stats</h3>
                                        <canvas id="team-chart" height="185" width="370" class="chartjs-render-monitor" style="display: block; width: 370px; height: 185px;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="au-card m-b-30">
                                    <div class="au-card-inner"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                        <h3 class="title-2 m-b-40">Top 5 Products This Year</h3>
                                        <canvas id="pieChart" height="246" width="370" class="chartjs-render-monitor" style="display: block; width: 370px; height: 246px;"></canvas>
                                    </div>
                                </div>
                            </div>
                       
                        
                    </div>
                </div>
            </section>
            <!-- END STATISTIC CHART-->

           

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
                          data: sale,
                          label: "Sale",
                          backgroundColor: 'rgba(0,103,255,.15)',
                          borderColor: 'rgba(0,103,255,0.5)',
                          borderWidth: 3.5,
                          pointStyle: 'circle',
                          pointRadius: 5,
                          pointBorderColor: 'transparent',
                          pointBackgroundColor: 'rgba(0,103,255,0.5)',
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
                              display: false,
                              drawBorder: false
                            },
                            scaleLabel: {
                              display: true,
                              labelString: 'Sales',
                              fontFamily: "Poppins"
                            },
                            ticks: {
                              fontFamily: "Poppins"
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
      ctx.height = 335;
      var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
          datasets: [{
            data: p_count,
            backgroundColor: [
              "rgba(0, 123, 255,1.0)",
              "rgba(0, 123, 255,0.8)",
              "rgba(0, 123, 255,0.6)",
              "rgba(0, 123, 255,0.4)",
              "rgba(0, 123, 255,0.2)",
            ],
            hoverBackgroundColor: [
              "rgba(0, 123, 255,1.0)",
              "rgba(0, 123, 255,0.8)",
              "rgba(0, 123, 255,0.6)",
              "rgba(0, 123, 255,0.4)",
              "rgba(0, 123, 255,0.2)",
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

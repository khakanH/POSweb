@extends('layouts.app')
@section('content')
  
  <style type="text/css">
    .statistic2 {
    padding-top: 10px;
}
  </style>
    
  
     <div class="page-content--bgf7">

            <section class="statistic statistic2">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">

                          <div class="card">
                 
                  <div class="card-body">
                      <nav>
                        <div class="nav nav-tabs" id="nav-tab_" role="tablist">
                          
                          <a class="nav-item nav-link active" id="nav-home-tab_" data-toggle="tab" href="#nav-home_" role="tab" aria-controls="nav-home_" aria-selected="false">1</a>&nbsp;&nbsp;
                          <a href="javascript:void(0)" class="nav-item nav-link btn btn-primary">+</a>&nbsp;&nbsp;
                          <a href="javascript:void(0)" class="nav-item nav-link btn btn-danger">-</a>
                        </div>
                      </nav>
                      <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home_" role="tabpanel" aria-labelledby="nav-home-tab">
                          
                         

                        </div>
                       
                       
                      </div>

                  </div>
                </div>


                        </div>
                        <div class="col-lg-8">
                          <div class="card">
                 
                  <div class="card-body">
                      <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                          <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" onclick="GetAllProducts()" aria-selected="false">Home</a>
                          @foreach($category as $cate)
                          <a class="nav-item nav-link" id="cate{{$cate['id']}}" data-toggle="tab" href="#nav-home" onclick='GetProductByCategory("{{$cate['id']}}")' role="tab" aria-controls="nav-profile" aria-selected="false">{{$cate['name']}}</a>
                          @endforeach
                        </div>
                      </nav>
                      <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                          <div class="form-group">
                                        
                                    <input class="au-input au-input--full" type="text" name="search_text" id="prod_search_text" required="" style="padding-right: 105px;" placeholder="Enter Product Name or Code" onfocusout="SearchProduct(this.value)">
                                    <button class="btn btn-primary" style="float: right;position: absolute; margin: 0px 0px 0px -43px; height: 44px;"><i class="fa fa-search"></i></button>
                                    </div>
                          <div class="row" id="pos-prod-list" style="min-height: 420px; max-height: 420px; overflow: auto;">
                          @foreach($product as $prod)
                          <div class="col-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mx-auto d-block">
                                            <img class="rounded-circle mx-auto d-block" src="{{env('IMG_URL')}}{{$prod['image']}}" width="100" height="100" alt="{{$prod['name']}}">
                                            <hr>
                                            <h5 class="text-sm-center mt-2 mb-1">{{$prod['name']}}</h5>
                                            <div class="location text-sm-center">
                                                {{$prod['price']}} /-</div>
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                          @endforeach
                          </div>

                        </div>
                       
                       
                      </div>

                  </div>
                </div>
                        </div>
                        
                    </div>
                </div>
            </section>

           

        </div>


        <script type="text/javascript">
          
          function GetAllProducts() 
          {
            txt =document.getElementById("prod_search_text").value.trim();

            if (!txt) 
            {
              txt = "0"; 
            }

            $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}get-pos-product-list/"+"0"+"/"+txt,
                     beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {
                            $('#LoadingModal').modal('hide');

                        $('#pos-prod-list').html(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });
          }
          function GetProductByCategory(cate_id) 
          { 
            txt =document.getElementById("prod_search_text").value.trim();

            if (!txt) 
            {
              txt = "0"; 
            }

            $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}get-pos-product-list/"+cate_id+"/"+txt,
                    beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {
                            $('#LoadingModal').modal('hide');

                        $('#pos-prod-list').html(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });
          }

        function SearchProduct(value)
        {   
            var txt = value.trim();

            if (!txt) 
            {
                txt = "0";
            }

                 $.ajax({
                    type: "GET",
                    url: "{{ env('APP_URL')}}get-pos-product-list/"+"0"+"/"+txt,
                    beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                    success: function(data) {
                            $('#LoadingModal').modal('hide');

                        $('#pos-prod-list').html(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Exception:' + errorThrown);
                    }
                });
        }
          
        </script>
                      


  
@endsection

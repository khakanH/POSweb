@extends('layouts.app')
@section('content')

  <!-- ________________________F R O A L A -- E D I T O R -- C S S______________________________ -->
 <!--  <link rel="stylesheet" href="{{ asset('froala_editor/css/froala_editor.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/froala_style.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/code_view.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/colors.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/emoticons.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/image_manager.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/image.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/line_breaker.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/table.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/char_counter.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/video.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/fullscreen.css') }}">
  <link rel="stylesheet" href="{{ asset('froala_editor/css/plugins/file.css') }}"> -->
  <!-- ______________________________________________________________________________________________ -->
  <style type="text/css">
     div#editor {
      width: 100%;
      margin: auto;
      text-align: left;
    }
  </style>


    
<div class="page-content--bgf7">
  <section class="statistic statistic2">
    <div class="container">
       <center>
            @if(session('success'))
                        <p class="text-success pulse animated">{{ session('success') }}</p>
                        {{ session()->forget('success') }}
                        @elseif(session('failed'))
                        <p class="text-danger pulse animated">{{ session('failed') }}</p>
                        {{ session()->forget('failed') }}
            @endif
            </center>


                        <div class="row">
                          <div class="col-lg-10"></div>
                          <div class="col-lg-2"><button class="au-btn au-btn-icon au-btn--green au-btn--small" onclick="AddCompany()" type="button">
                          <i class="zmdi zmdi-plus"></i>Add New Comapany</button></div>
                        </div>
                          <br>
      <div class="row">
        <div class="col-lg-12">


                 


          <div class="card">
              <div class="card-header">
                <strong>Company</strong>
                <small> List</small>
              </div>
              
              <div class="card-body card-block">
                  
                  <div class="row" id="">
                          @foreach($company as $key)
                          <div class="col-lg-3">
                                <div class="card" style="cursor: pointer;" onclick='EditCompany("<?php echo $key['id'] ?>")'>
                                    <div class="card-body">
                                        <div class="mx-auto d-block">
                                            <img style="height: 100px;" class="mx-auto d-block" src="{{env('IMG_URL')}}{{$key['logo']}}" width="100" height="100" alt="{{$key['name']}}">
                                            <hr>
                                            <h5 class="text-sm-center mt-2 mb-1">{{$key['name']}}</h5>
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
  </section>
</div>   


<!-- ________________________F R O A L A -- E D I T O R -- J S ______________________________ -->
 <!--  <script type="text/javascript" src="{{ asset('froala_editor/js/froala_editor.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('froala_editor/js/plugins/align.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('froala_editor/js/plugins/colors.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('froala_editor/js/plugins/font_size.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('froala_editor/js/plugins/font_family.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('froala_editor/js/plugins/lists.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('froala_editor/js/plugins/paragraph_format.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('froala_editor/js/plugins/paragraph_style.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('froala_editor/js/plugins/inline_style.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('froala_editor/js/plugins/save.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('froala_editor/js/plugins/fullscreen.min.js') }}"></script> -->
<!-- ________________________________________________________________________________________ -->



<script type="text/javascript">
  @if (session('login.is_set_profile') == 0) 
  {
    document.addEventListener("DOMContentLoaded", function(event) { 
        EditCompany(<?php echo session('login.company_id'); ?>);
        document.getElementById("set-profile-msg").innerHTML ='<br><center><p class="tx-danger">Kindly Save Your Company Information First!</p></center><br>'
    });
  }
  @endif
// (function () {
//       new FroalaEditor("#edit", {
//         // Set the list of available shortcuts.
//         shortcutsEnabled: ['bold', 'italic'],

//         // Disable shortcut hints.
//         shortcutsHint: false
//       })
//     })();
    
  
    function FBRToggle(val)
    {
      if (val == "1") 
      {
        document.getElementById("company_fbr").value = "0";
        document.getElementById("company_fbr").checked = false;
        document.getElementById("company_pos_id_div").style.display = "none";

      }
      else
      {
        document.getElementById("company_fbr").value = "1";
        document.getElementById("company_fbr").checked = true; 
        document.getElementById("company_pos_id_div").style.display = "block";

      }
    }




  var logo_loadFile = function(event) {
    var company_logo_output = document.getElementById('company_logo_output');
    company_logo_output.src = URL.createObjectURL(event.target.files[0]);
    company_logo_output.onload = function() {
      URL.revokeObjectURL(company_logo_output.src) // free memory
    }};

    function clearImage()
    {
      document.getElementById('company_logo_output').src="{{ env('IMG_URL')}}choose_img.png";
    }




// ______________________________________________________________________________________________
// ______________________________________________________________________________________________
// ______________________________________________________________________________________________



    function AddCompany()
    {
        document.getElementById("set-profile-msg").innerHTML = "";
        document.getElementById('company_name').value = "";
        document.getElementById('company_phone').value = "";
        document.getElementById('company_email').value = "";
        document.getElementById('company_default_discount').value = "";
        document.getElementById('company_default_tax').value = "";
        
        document.getElementById('company_fbr').checked = false;
        document.getElementById('company_fbr').value = "0";
        document.getElementById('company_pos_id_div').style.display = "none";
        document.getElementById('company_pos_id').value = "";

        document.getElementById('company_receipt_header').value = "";
        document.getElementById('company_receipt_footer').value = "";



        document.getElementById('company_id').value = "";
        document.getElementById("company_logo").value = "";
        document.getElementById('company_logo_output').src = "{{ env('IMG_URL')}}choose_img.png";
        $('#CompanyModal').modal('show');
        $('#CompanyModalLabel').html('Add New Company');

        document.getElementById('CompanyModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('CompanyModalDialog').style.paddingTop="0px";
        document.getElementById('CompanyModalData').style.padding="20px 20px 0px 20px";


        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-country-name-list",
        success: function(data) {

            $('#company_country').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });
    }



    function EditCompany(id)
    {
        $('#CompanyModal').modal('show');
        $('#CompanyModalLabel').html('Edit Company');

        document.getElementById('CompanyModal').style.backgroundColor="rgba(0,0,0,0.8)";
        document.getElementById('CompanyModalDialog').style.paddingTop="0px";
        document.getElementById('CompanyModalData').style.padding="20px 20px 0px 20px";

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}get-company-info/"+id,
        success: function(data) {

            $('#CompanyModalData').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
    });


    }










</script>


  
@endsection

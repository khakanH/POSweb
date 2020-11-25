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
        <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <strong>Company</strong>
                                        <small> Information</small>
                                    </div>
                                    <div class="card-body card-block">
                                      <form name="companyInfoForm" id="infoForm" method="post">
                                        @csrf
                                        <span class='arrow'>
                                        <label class='error'></label>
                                        </span>
                                        <div class="row form-group">
                                          <div class="col-lg-6">
                                            <label for="name" class=" form-control-label">Company Name</label>
                                            <input type="text" id="name" name="name" placeholder="Enter your company name" value="{{$company->name}}" class="form-control">
                                          </div>
                                          <div class="col-lg-6">
                                            <label for="phone" class=" form-control-label">Company Phone</label>
                                            <input type="text" id="phone" name="phone" placeholder="Enter your company phone" value="{{$company->phone}}" class="form-control">
                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-6">
                                            <label for="email" class=" form-control-label">Company Email</label>
                                            <input type="email" id="email" name="email" placeholder="Enter your company email address" value="{{$company->email}}" class="form-control">
                                          </div>
                                          <div class="col-lg-6">
                                            <label for="country" class=" form-control-label">Company Country</label>
                                            <select class="form-control" name="country" id="country">
                                              <option value="" disabled="" selected="">Select Country</option>
                                              @foreach($country as $key)
                                                <option <?php if ($company->country_id == $key['id']): ?>
                                                    selected
                                                <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>
                                              @endforeach
                                            </select>
                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-6">
                                            <label for="discount" class=" form-control-label">Default Discount %</label>
                                            <input type="number" id="discount" name="discount" placeholder="Enter your company default discount" value="{{$company->default_discount}}" min="0" max="100" class="form-control">
                                          </div>
                                          <div class="col-lg-6">
                                            <label for="tax" class=" form-control-label">Default Tax %</label>
                                            <input type="number" id="tax" name="tax" placeholder="Enter your company default tax" value="{{$company->default_tax}}" min="0" max="100" class="form-control">
                                          </div>
                                        </div>

                                        <div class="row form-group" style="background: #333; border: solid gray 2px; border-radius: 3px; color: white; padding: 25px;">
                                          <div class="col-lg-6">
                                            <label class="form-control-label">FBR Invoice Data:</label>
                                            &nbsp;&nbsp;&nbsp;
                                            <label class="switch switch-3d switch-primary switch-lg mr-3">
                                              <input onclick="FBRToggle(this.value)" type="checkbox" class="switch-input" id="fbr_input" name="fbr_input" <?php if ($company->fbr_invoice ==1): ?>
                                                  checked
                                              <?php endif ?> value="{{$company->fbr_invoice}}">
                                              <span class="switch-label"></span>
                                              <span class="switch-handle"></span>
                                            </label>
                                          </div>
                                          <div class="col-lg-6" id="POSID_div" <?php if ($company->fbr_invoice ==1): ?>
                                            style="display: block;"
                                          <?php else: ?>
                                            style="display: none;"
                                          
                                          <?php endif ?> >

                                            
                                            <label class="form-control-label" style="">POS ID:</label>&nbsp;&nbsp;&nbsp;
                                            <input required="" type="text" class="form-control" name="pos_id" value="{{$company->pos_id}}">


                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-12">
                                            <label for="company_logo_output" class="form-control-label">Company Logo:</label><br>
                                            <img id="company_logo_output" src="{{ env('IMG_URL')}}{{$company->logo}}" width="130" height="130" style="border-radius: 2%; border: solid gray 1px; object-position: top; object-fit: cover;">&nbsp;&nbsp;&nbsp;<input type="file" onchange="logo_loadFile(event)" onclick="clearImage()"   name="company_logo"  accept="image/*" >
                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-12">
                                            <label for="receipt_header" class="form-control-label">Text in the Receipt Header:</label>
                                            <!-- <div id="editor">
                                            <div id='edit' style="margin-top: 10px;"><p id="receipt_header">{!! $company->receipt_header !!}</p>
                                            </div>
                                            </div> -->
                                            <textarea id="receipt_header" placeholder="Enter receipt header text" name="receipt_header" class="form-control" rows="4">{{$company->receipt_header}}</textarea>
                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-12">
                                            <label for="receipt_footer" class="form-control-label">Text in the Receipt Footer:</label>
                                            <input type="text" id="receipt_footer" name="receipt_footer" placeholder="Enter receipt footer text" value="{{$company->receipt_footer}}" class="form-control">
                                            
                                          </div>
                                        </div>

                                        <div class="row form-group">
                                          <div class="col-lg-9"></div>
                                          <div class="col-lg-3"><button style="width: 100%;" type="submit" class="btn btn-info">Save</button></div>
                                        </div>

                                      </form>
                                        
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
        document.getElementById("fbr_input").value = "0";
        document.getElementById("fbr_input").checked = false;
        document.getElementById("POSID_div").style.display = "none";

      }
      else
      {
        document.getElementById("fbr_input").value = "1";
        document.getElementById("fbr_input").checked = true; 
        document.getElementById("POSID_div").style.display = "block";

      }
    }


    $.validator.addMethod('emailFormat', function(value, element) {
        return this.optional(element) || (value.match(/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/));
    },
    'Please enter a valid email address.');
  

    $(function() {
        $("form[name='companyInfoForm']").validate({
             errorPlacement: function(label, element) {
        label.addClass('arrow');
        label.css({"color": "red", "font-size": "12px" ,"width":"100%"});
        label.insertAfter(element);
    },
    wrapper: 'span',

    rules: {
      name: {
        required: true,
      },
       email: {
        required: true,
        emailFormat: true,
      },
       phone: {
        required: true,
      },
      country: {
        required: true,
      },
      company_logo: {
        required: false,
      },
      pos_id: {
        required: true,
        minlength: 6,
        maxlength: 6,
        digits: true,
      },
    },
    messages: {
      name: {
        required: "Please Provide a Company Name",
      },
       email: {
        required: "Please Provide a Company Email Address",
      },
       phone: {
        required: "Please Provide a Company Phone Number",
      },
      country: {
        required: "Please Select a Company Country",
      },
      company_logo: {
        required: "Please Provide a Company Logo",
      },
       pos_id: {
        required: "Please Provide a POS ID",
        minlength: "POS ID Must be of 6 Digits",
        maxlength: "POS ID Must be of 6 Digits",
      },
     
    },
    submitHandler: function(form) {

        let myForm = document.getElementById('infoForm');
        let formData = new FormData(myForm);

         $.ajax({
        type: "POST",
        url: "{{ env('APP_URL')}}add-company-info",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
        success: function(data) {
           
                            $('#LoadingModal').modal('hide');

            get_status = data['status'];
            get_msg    = data['msg'];

                            if (get_status == "0") 
                            {

                             document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-danger alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = "Failed, Try Again Later";


                                 setTimeout(function() {
                                document.getElementById('toast').className = "alert alert-danger alert-rounded fadeOut animated";

                            }, 5000);

                            }
                            else
                            {
                                document.getElementById('toast').style.visibility = "visible";
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeIn animated";
                                document.getElementById('toastMsg').innerHTML = get_msg;


                                 setTimeout(function() {
                                document.getElementById('toast').className = "alert alert-success alert-rounded fadeOut animated";

                            }, 5000);


                            }


    }
  });

    }
  });
  });






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
</script>


  
@endsection

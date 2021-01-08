    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/font-face.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/font-awesome-4.7/css/font-awesome.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/font-awesome-5/css/fontawesome-all.min.css') }}" rel="stylesheet" media="all">
    
    <link href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" rel="stylesheet">

    <link href="{{ asset('vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <!-- <link href="{{ asset('vendor/bootstrap-4.1/bootstrap.min.css?'.time().'') }}" rel="stylesheet" media="all"> -->

    <!-- Vendor CSS-->
    <link href="{{ asset('vendor/animsition/animsition.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/wow/animate.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/slick/slick.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" media="all">

    <link href="{{ asset('css/theme.css?'.time().'') }}" rel="stylesheet" media="all">

    

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('assets/css/login-resgister.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/pos.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/dashboard.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <style type="text/css">
         ::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.1);
    background-color: rgba(0,0,0,0);
    border-radius: 10px;
}

::-webkit-scrollbar
{
    width: 11px;
    height: 10px;
    background-color: rgba(0,0,0,0);
}

::-webkit-scrollbar-thumb
{
    border-radius: 10px;
    background-color: #FFF;
    background-image: -webkit-gradient(linear,
                                       40% 0%,
                                       75% 84%,
                                       from(#6c6d70),
                                       to(#6c6d70),
                                       color-stop(.6,#6c6d70))
}
  

          .tx-12{
            font-size: 12px;
        }
        .tx-14{
            font-size: 14px;
        }
        .tx-16{
            font-size: 16px;
        }
        .tx-18{
            font-size: 18px;
        }
        .tx-20{
            font-size: 20px;
        }
        .tx-22{
            font-size: 22px;
        }
        .tx-24{
            font-size: 24px;
        }
        .tx-26{
            font-size: 26px;
        }
        .tx-28{
            font-size: 28px;
        }
        .tx-30{
            font-size: 30px;
        }
        .tx-32{
            font-size: 32px;
        }
        .tx-34{
            font-size: 34px;
        }
        .tx-36{
            font-size: 36px;
        }
        .tx-38{
            font-size: 38px;
        }
        .tx-40{
            font-size: 40px;
        }
        .tx-42{
            font-size: 42px;
        }
        .tx-44{
            font-size: 44px;
        }

        .tx-success{
            color: #218f23;
        }
        .tx-danger{
            color: #b81a1a;
        }
        .tx-white{
            color: white;
        }
        .v-align-middle{
        vertical-align: middle;
        }
        .tx-center{
            text-align: center;
        }


        .bg-info{
            background: #00b5e9;
        }
        .bg-warning{
            background: #ffc107;
        }
        .bg-danger{
            background: #fa4251;
        }
        .bg-other{
            background: #212529;
        }


        /*Data-tables styling*/

          button.dt-button{
         font-size: 16px !important;
    background-color: #3a3e97;
    
    width: auto;
    border: none;
    border-radius: 50px;
    font-weight: 400;
    color: #ffffff;
    cursor: pointer;
        }

         button.dt-button:hover {
    color: #000;
     background-color: #3a3e97 !important;
    border-color: #1a8fc2
}
       .dataTables_wrapper .dataTables_paginate .paginate_button {
               color: #000 !important;
                background: #e6e8f1 !important;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08);
                margin: 0px 5px 0px 5px;
       }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled)  {
               color: #fff !important;
                background: #3a3e97 !important;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08);
                margin: 0px 5px 0px 5px;
       }
       .dataTables_wrapper .dataTables_paginate .paginate_button.current {
               color: #fff !important;
                background: #3a3e97 !important;
                border-color: transparent !important;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08)
       }
       
       .dataTables_wrapper .dataTables_filter input{
    border-radius:10px;
    border: none;
    background-color: rgba(230, 232, 241, 1);
       }
       table.dataTable{
        padding-top: 15px;
        margin-bottom: 10px;
       }
    </style>
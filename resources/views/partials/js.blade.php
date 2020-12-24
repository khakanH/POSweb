    <script src="{{asset('vendor/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{ asset('vendor/bootstrap-4.1/popper.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-4.1/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/animsition/animsition.min.js') }}"></script>
    <script src="{{ asset('vendor/chartjs/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script> -->

   

    <script type="text/javascript">

         $( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {


            if (jqxhr.status == 401) 
            {
                alert("Session expired. You'll be take to the login page");
                location.href = "{{ env('APP_URL')}}"; 
            }
            else if(jqxhr.status == 403)
            {
                alert("Sorry, You're not allowed to visit requested page. Taking you to Dashboard Page.");
                location.href = "{{ env('APP_URL')}}";
            }
            else if(jqxhr.status == 440)
            {   
                //for admin
                alert("Session expired. You'll be take to the login page");
                location.href = "{{ env('APP_URL')}}admin";
            }
            else
            {
                alert("Something Went Wrong");
            }
    

    });
        function hideToast(id)
        {
            document.getElementById(id).style.visibility = "hidden";
        }
       
    </script>
   
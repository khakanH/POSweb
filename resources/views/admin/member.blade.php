@extends('layouts.admin_app')
@section('content')
       

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
                        
       <div class="user-data m-b-30">
                  
                                    <div class="table-responsive table-data">
                                        <table class="table text-center">
                                            <thead>
                                                <tr>
                                                    <td width="2%">
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td>
                                                    <td width="5%">name</td>
                                                    <td width="15%">email</td>
                                                    <td width="10%">account</td>
                                                    <td width="10%">profile</td>
                                                    <td width="20%" class="text-center">Action</td>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 14px;">
                                                @foreach($members as $key)
                                                <tr id="row{{$key['id']}}">
                                                    <td>
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        {{$key['username']}}
                                                    </td>
                                                    <td>
                                                        {{$key['email']}}
                                                        
                                                    </td>
                                                    <td>
                                                        @if($key['is_verified'] == 0)
                                                        <span class="role admin">Not Verified</span>
                                                        @else
                                                        <span class="role member">Verified</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($key['is_set_profile'] == 0)
                                                        <span class="role admin">Not Updated</span>
                                                        @else
                                                        <span class="role member">Updated</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                         <a data-toggle="tooltip" title="Block/Unblock Member" id="status-btn-color<?php echo $key['id']?>" class="<?php if ($key['is_blocked'] == 0): ?>btn btn-danger<?php else: ?>btn btn-success<?php endif ?>" href="javascript:void(0)" onclick='BlockUnblockMember("<?php echo $key['id']?>")'><i id="status-btn-icon<?php echo $key['id']?>"  class="
                                                    <?php if ($key['is_blocked'] == 0): ?>
                                                    fa fa-lock tx-15
                                                    <?php else: ?>
                                                    fa fa-unlock tx-15
                                                    <?php endif ?>

                                                    "></i></a>

                                                    <a data-toggle="tooltip" title="View Member Details" class="btn btn-primary" href="javascript:void(0)" onclick='ViewMember("<?php echo $key['id']?>")'><i class="fa fa-eye tx-15"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

    
    </div>
  </div>
</div>


<script type="text/javascript">
    function ViewMember(id)
    {
        $('#MemberDetailModal').modal('show');
        $('#MemberDetailModalData').html("");

        $.ajax({
        type: "GET",
        url: "{{ env('APP_URL')}}admin/get-member-details/"+id,
        beforeSend: function(){
                             document.getElementById("MemberDetailModalLoading").style.display = "block";
                          },
        success: function(data) {
                             document.getElementById("MemberDetailModalLoading").style.display = "none";


            $('#MemberDetailModalData').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Exception:' + errorThrown);
        }
        });   
    }
</script>


@endsection

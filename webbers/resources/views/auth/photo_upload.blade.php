@extends('layouts.app')
@section('content')
<div class="container" style="position: relative;top: 80px">
    <div class="row justify-content-center">
        <div class="col-md-6 border-0" style="margin-top: 20px;">
            <div class="card pl-3 pt-2 pr-3">
                <div class="">
                    <span class="text-primary float-right to_selection" data-action="to-selection" data-id="{{Auth::user()->id}}" title="Proceed" style="cursor: pointer">Skip <i class="fa fa-arrow-right"></i></span>
                    <div class="text-dark" style="font-size: 27px">{{ __('Profile Photo Upload') }}</div>
                </div>
                <div class="card-body">
                    @if (session('resent'))
                    <div class="alert alert-light" role="alert" style="border-left: 3px solid green">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                    @endif
                    
                    {{ __('Hi '.Auth::user()->name.',')}}<br>
                    {{ __('Thanks for verifying your account.') }}
                    {{__('As part of our user profiling techniques, we would like you to upload a profile photo. However, this action is optional, as it can be done at anytime provided you have verified your account.')}}
                    
                    <div class="user-image mt-2 text-center">
                        <img src="{{asset('/storage/'.Auth::user()->profile->avatar)}}" width="70">
                    </div>         
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    
    $(document).ready(function(){
        $(".to_selection").click(function()
        {
            let id = $(this).attr("data-id");
            let data_action = $(this).attr("data-action");
            
            //tell the webserver to make an update since we cannot go to the next stage without update
            $.ajax({
                url: "{{route('photo_upload_proceed')}}",
                type: "PATCH",
                data:{id:id,action:data_action,_token:"{{csrf_token()}}"},
                dataType:"json",
                success:function(result)
                {
                    if(result.success)
                    {
                        window.location.href = result.next;
                    }
                }
            });
        });
    })
    
</script>
@endsection



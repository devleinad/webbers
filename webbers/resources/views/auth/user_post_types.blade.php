@extends('layouts.app')
@section('content')
<div class="container" style="position: relative;top: 50px">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card pl-3 pt-2 pr-3 border">
                <div class="">
                    <a href="{{route('photo_upload')}}" class="text-primary float-left" title="Go Back" style="border-radius: 50px"><i class="fa fa-arrow-left"></i> Back </a>
                    <span class="text-primary float-right to-home" title="Finish" 
                    style="border-radius: 50px; display:none; cursor:pointer">Finish <i class="fa fa-arrow-right"></i></span>
                </div>
                <div class="card-body">
                    @if (session('resent'))
                    <div class="alert alert-light" role="alert" style="border-left: 3px solid green">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                    @endif
                    
                    {{ __('Okay '.Auth::user()->name.',')}}<br>
                    {{ __('You are almost done.') }}
                    {{__('Why don\'t you go ahead and select some category of posts that you would like to see.
                    Make at least 5 selections.')}}
                    
                    <div class="categories mt-2">
                        
                        <span class="text-dark font-weight-bold display-5">Your current selections : <i>(<span class="categories_count"></span>)</i></span>   &nbsp;
                        <span class="text-primary reset" data-id="{{Auth::user()->id}}" style="cursor: pointer; border-radius:50px; display:none">Undo</span>
                        
                        <div class="row categories_row"> 
                        </div>
                        <div class="mt-2 text-center">
                            <h6 class="text-dark">Cannot find that special or favourite category?</h6>
                            <h6 class="text-dark">Let us know <a href="">here</a></h6>
                        </div>
                    </div>
                    
                    
                    
                </div>
            </div>
        </div>
        
        
    </div>
</div>


@section('scripts')
<script>
    $(document).ready(function(){
        
        getDifferentCategories();
        getUserCategoriesCount(); 
        
        function getDifferentCategories()
        {
            let user_id = "{{Auth::user()->id}}";
            $.ajax({
                url : "{{route('user_choice')}}",
                type:"GET",
                data:{user_id:user_id,action:'fetch_different_categories'},
                dataType:'json',
                success:function(result)
                {
                    if(result.success)
                    {
                        $(".categories_row").html(result.data);
                    }
                }
            })
        }
        
        $(document.body).on('click','.category', function(){
            let category_id = $(this).attr('data-id');
            let _token = "{{csrf_token()}}";
            $(this).toggleClass(['selected','bg-primary','text-white','border-0']);
            if($(this).hasClass('selected'))
            {
                $.ajax({
                    url : "{{route('user_choice')}}",
                    type : 'POST',
                    data : {category_id:category_id,action:'selected',_token:_token},
                    dataType:'json',
                    success:function(result)
                    {
                        console.log(result.success);
                        getUserCategoriesCount();
                        getDifferentCategories();
                    }
                    
                });
            }
            else{
                $.ajax({
                    url : "{{route('user_choice_destroy')}}",
                    type : 'DELETE',
                    data : {category_id:category_id,action:'unselected',_token:_token},
                    dataType:'json',
                    success:function(result)
                    {
                        console.log(result.success);
                        getUserCategoriesCount();
                    }
                    
                });
            }
            
        });
        
        function getUserCategoriesCount()
        {
            let user_id = "{{Auth::user()->id}}";
            $.ajax({
                url : "{{route('user_choice')}}",
                type:"GET",
                data:{user_id:user_id,action:'get_user_categories_count'},
                dataType:'json',
                success:function(result)
                {
                    if(result.success == true)
                    {
                        $(".categories_count").text(result.count);
                        if(result.count > 0)
                        {
                            $(".reset").show();
                        }
                        
                        if(result.count >= 5)
                        {
                            $(".to-home").show();
                        }
                    }
                }
            })
        }
        
        $(document.body).on('click','.reset',function(){
            $.ajax({
                url:"{{route('user_choice')}}",
                type:"DELETE",
                data:{user_id:$(this).attr('data-id'),action:'reset',_token:"{{csrf_token()}}"},
                dataType:"json",
                success:function(result)
                {
                    if(result.success)
                    {
                        getUserCategoriesCount()
                        getDifferentCategories();
                    }
                }
            });
        });
        
        $(".to-home").on('click', function()
        {
            let _token = "{{csrf_token()}}";
            $.ajax({
                url:"{{route('user_choice')}}",
                type:"PATCH",
                data:{user_id:"{{Auth::user()->id}}",action:"finalise",_token:_token},
                dataType:"json",
                success:function(result)
                {
                    if(result.success)
                    {
                        window.location.href = "{{route('home')}}";
                    }
                }
            })
        })
        
        
    });
    
</script>

@endsection

@endsection



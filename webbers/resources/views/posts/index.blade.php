@extends('layouts.app')
@section('css')
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row main-row">
        <div class="col-lg-2 col-md-2 col-sm-2 ml-3">
            @include('includes.sidenav')
        </div>
        <div class="col-lg-lg-6 col-md-6 col-sm-6 col-xs-6" style="margin-left: 25px">
            @if(Auth::user()->setting->ushured == 0)
            <div class="alert alert-light alert-dismissible ml-3 alert-ushur border-0" role="alert">
                <h4 class="alert-heading">Congratulations!</h4>
                <p class="text-dark">
                    Aww yeah <a href="">{{'@'.Auth::user()->username}}</a>, you have successfully completed the sign up process. 
                    You are officially a <b>webber :)</b>. 
                    Click <a href="">here</a> for site navigation assiistance.   
                </p>
                <button type="button" class="close ushured" data-action="close-alert-ushur" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <div class="post-container pb-2 pt-2">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{session('success')}}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                
                @if(session('access_denied'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{session('access_denied')}}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>{{session('error')}}</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                
                @if($posts->count() > 0)
                @foreach($posts as $post)
                <div class="post p-2 bg-white mb-1 border-bottom border-top" id="{{$post->id}}">
                    <div class="post-head">
                        <div class="top">
                            <div class="dropdown float-right">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @if(isHidden($post->id))
                                    <a class="dropdown-item hide" href="" id={{$post->id}}><i class="fa fa-eye m-r-5"></i> Show</a>
                                    @else
                                    <a class="dropdown-item hide" href="" id={{$post->id}}><i class="fa fa-eye m-r-5"></i> Hide</a>
                                    @endif
                                    @can('update',$post)
                                    <a class="dropdown-item" href="{{route('questions.edit',['identifier' => $post->identifier])}}" id="{{$post->id}}"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                    <a class="dropdown-item text-danger" href="" onclick="event.preventDefault(); document.getElementById('delete_post').submit();"><i class="fa fa-trash m-r-5"></i> Delete</a>
                                    <form method="POST" action="{{route('questions.destroy',['post' => $post->id])}}" id="delete_post" style="display: none">
                                        @csrf
                                        @method("DELETE")
                                    </form>
                                    
                                    @endcan
                                    
                                </div>
                            </div>
                            
                            <a href="" class="text-dark">
                                <img src="{{getAvatar($post->user_id)}}" width="30">
                                <small> {{$post->user->username}} {{$post->user->presentReputation()}}</small> 
                            </a> &nbsp;
                            <small class="text-muted" style="margin-top:10px">
                                {{$post->presentDateTime() }}
                            </small>
                            &nbsp;
                            @if(isBountied($post->id))
                            <small>{{$post->presentBounty()}}</small>
                            @endif
                        </div>    
                    </div>
                    
                    <div class="post-content ml-4 p-2">
                        <div class="post-title mt-1 mb-1">
                            <a href="{{route('posts.show',['identifier' => $post->identifier,])}}" 
                                class="display-5 font-weight-bold text-primary">{{$post->post_title}} </a>
                            </div>
                            
                            <div class="post-content">
                                <div class="pt-2">
                                    <?= getTrimmedContent($post->identifier,$post->post_body);?>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="post-tag">
                                            <span>
                                                <small class="text-dark">
                                                    Tagged: <i class="fa fa-tag"></i> 
                                                    {{ $post->post_category }}
                                                </small>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div class="row">
                                            <div class="col-lg-4 text-center">
                                                <span><small><i class="fa fa-comments"></i> ({{presentPostCommentsCount($post->id)}})</small></span>
                                            </div>
                                            
                                            <div class="col-lg-4 text-center">
                                                <span style="cursor: pointer"><small><i class="fa fa-heart-o"></i> (1000)</small></span>
                                            </div>
                                            
                                            <div class="col-lg-4 text-center">
                                                <span><small><i class="fa fa-eye"></i> ({{$post->presentPostViews()}})</small></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                    @endforeach
                    <div class="text-center mt-3 mb-3">
                        {{$posts->links()}}
                    </div>
                    @else
                    
                    <div class="bg-white p-3 text-center">
                        <h5>Ouch! There aren't any posts yet. </h5>
                        <small class="text-muted">Remember: Only posts under categories you have followed will appear. Read more <a href="">here</a></small>
                    </div>
                    
                    @endif
                </div>
                
            </div>
            
            <!--Start of right col-3 -->
            <div class="col-lg-3 col-md-3 ml-4">
                <div class="right-col-3 bg-white">
                    <div class="articles p-2">
                        <h6>Recommended Articles</h6>
                        <div class="article p-2 mb-2 bg-white border-top border-bottom border-right" style="border-left: 3px solid #82E0AA">
                            <div class="author d-flex">
                                <a href="" class="text-dark">
                                    <img src="https://www.gravatar.com/avatar/5a753a7da00760f1b7a2d94fbfda7b45.jpg?d=identicon&s=150&r=pg" width="30"> Paulo Henrique S.S.</a>
                                </div>
                                <div class="article-title mt-1" style="font-size: 12px">
                                    <a href="https://www.codeproject.com/Articles/1029482/A-Beginners-Tutorial-for-Understanding-and-Imple-2"> 
                                        A Beginner's Tutorial for Understanding and Implementing a CRUD APP USING Elasticsearch and C# - Part 1
                                    </a>
                                </div>
                            </div>
                            
                            <div class="article p-2 bg-white border-top border-bottom border-right" style="border-left: 3px solid #82E0AA">
                                <div class="author d-flex">
                                    <a href="" class="text-dark">
                                        <img src="https://www.gravatar.com/avatar/6e66bbbd727d279fe5237f6e11b0a5a0.jpg?d=identicon&s=150&r=pg" width="30"> 
                                        Łukasz Bownik
                                    </a>
                                </div>
                                <div class="article-title mt-1" style="font-size: 12px">
                                    <a href="https://www.codeproject.com/Articles/5061258/The-Psychological-Reasons-for-Software-Project-Fai">
                                        The Psychological Reasons for Software Project Failures
                                    </a>
                                </div>
                            </div>
                            
                            <div class="mt-2 p-2 text-center">
                                <h6>Do you want to create your own article?</h6>
                                <a href="" class="btn btn-md article-btn text-white shadow-sm"
                                style="background: #F1948A; border-radius:50px">Start Here <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End of right col-3 -->
            </div>
        </div>
        @endsection
        
        @section('scripts')
        <script>
            $(document).ready(function()
            {
                
                $(".close-ok-with-categories").click(function()
                {
                    let action = $(this).attr('data-action');
                    $.ajax({
                        url:"{{route('user_choice')}}",
                        type:"PATCH",
                        data:{action:action,_token:"{{csrf_token()}}"},
                        success:function(result)
                        {
                            if(result.success)
                            {
                                $(".ok-with-categories").hide();
                            }
                        }
                    })
                });
                
                $(".ushured").click(function()
                {
                    let action = $(this).attr('data-action');
                    $.ajax({
                        url:"{{route('user_choice')}}",
                        type:"PATCH",
                        data:{action:action,_token:"{{csrf_token()}}"},
                        success:function(result)
                        {
                            if(result.success)
                            {
                                $(".alert-ushur").hide();
                            }
                        }
                    })
                });
                
                
            });
            
            $(".hide").click(function(e){
                e.preventDefault();
                var id = $(this).attr('id');
                $(this).parent().parent().parent().parent().parent().hide();
                axios.post("/questions/hide_unhide",{
                    id : id,
                    action:"hide",
                    _token:"{{csrf_token()}}",
                    dataType:"json",
                }).then(function(response){
                    console.log(response);
                })
            })
            
            
        </script>
        
        @endsection
        
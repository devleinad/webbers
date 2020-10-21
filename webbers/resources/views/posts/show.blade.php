@extends('layouts.app')
@section('css')
<script src="{{asset('ckeditor/ckeditor.js')}}"></script>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row main-row">
        <div class="col-lg-2 col-md-2 col-sm-2 ml-3">
            @include('includes.sidenav')
        </div>
        <div class="col-lg-lg-6 col-md-6 col-sm-6 col-xs-6 bg-white" style="margin-left: 30px">
            <div class="post-container pb-2">
                <div class="post p-2 bg-white mb-1" id="top">
                    <div class="post-head">
                        <div class="top">
                            
                            
                            <a href="" class="text-dark">
                                <img src="{{getAvatar($post->user_id)}}" width="30">
                                <small> {{$post->user->username}} {{$post->user->presentReputation()}}</small> 
                            </a> &nbsp;
                            <small class="text-muted" style="margin-top:10px">
                                {{$post->presentDateTime() }}
                            </small>
                        </div>    
                    </div>
                    
                    <div class="post-content ml-4 p-2">
                        <div class="post-title mt-1 mb-1">
                            <a href="{{route('posts.show',['identifier' => $post->identifier,])}}" class="display-5 font-weight-bold text-primary"> {{$post->post_title}} </a>
                        </div>
                        
                        <div class="post-content">
                            <div class="pt-2">
                                {!! $post->post_body !!}
                            </div>
                            <div class="row mt-3">
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
                                            <span><small>Comments ({{presentPostCommentsCount($post->id)}})</small></span>
                                        </div>
                                        
                                        <div class="col-lg-4 text-center">
                                            <span><small>Views ({{$post->presentPostViews()}})</small></span>
                                        </div>
                                        
                                        <div class="col-lg-4 text-center">
                                            <span><small>Reports (0)</small></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        
                    </div>
                    
                </div>
                
            </div>
            
            
            @if($comments->count() > 0)
            
            <div class="comment-box pt-1 pb-1">
                @foreach($comments as $comment)
                <div class="comment mb-1 pl-2 pr-2 pt-1 pb-1 border-bottom border-top" @if($comment->is_best_answer == 1) style="background:#FFF5EE" @endif>
                    
                    @can('update',$post)
                    <div class="comment-like float-right text-center">
                        <small>
                            <form id="accept_form" method="POST" action="{{route('comments_accept',['post' => $post->id,'comment' => $comment->id])}}">
                                @csrf
                                @method("PATCH")
                                <button type="submit" class="border-0" style="background: inherit"><i class="fa fa-handshake-o mt-1 text-primary" title="Accept as best answer"></i></button>
                                
                            </form>
                        </small>
                    </div>
                    @endcan
                    
                    <div class="comment-edit float-right text-center mr-2">
                        <small>
                            @if (Auth::user()->likes()->where('comment_id', $comment->id)->first()) 
                            <i class="fa fa-heart-o text-danger liked" data-id={{$comment->id}} style="cursor:pointer"></i>
                            <sup class="likes_count"></sup>
                            @else
                            <i class='fa fa-heart-o text-primary like' data-id={{$comment->id}} style="cursor:pointer"></i>
                            <sup class="likes_count"></sup>
                            @endif
                            
                        </small>
                        
                    </div>
                    
                    <div class="comment-edit float-right text-center mr-2">
                        <small><a href=""> <i class="fa fa-pencil" title="Edit this comment"></i></a></small>
                    </div>
                    
                    
                    <span>
                        <img src="{{getAvatar($comment->user->id)}}" width="23" class="pb-1"> 
                        <span><small class="text-primary">{{$comment->user->username}}</small></span> &nbsp; 
                        <small>{{$comment->presentCommentTime()}}</small>
                    </span> 
                    <div class="comment-text pl-4">
                        {!! $comment->comment !!}
                    </div>
                    
                    @if($comment->is_best_answer == 1) 
                    <div class="text-right">
                        <i class="fa fa-check-circle text-success"></i>
                    </div>
                    @endif
                </div>
                
                @endforeach
            </div>
            
            <div class="hide-post-content text-right mt-2">
                <a href="#" class="text-dark" data-target="#top"><i class="fa fa-caret-up"></i></a>
            </div>
            
            @else
            <div class="ml-4 text-center">
                <small class="text-danger">Sorry, no comments available yet. But you can do that <img src="https://img.icons8.com/emoji/48/000000/backhand-index-pointing-down-medium-dark-skin-tone.png"/ width="23"></small>
            </div>
            @endif
            
            @if(false == blockedUser(Auth::id()))
            <div class="new-comments-box" style="margin-top:50px">
                <div class="text-center">
                    <h4 class="mb-2 font-weight-bold">Would you like to share your thoughts on this topic?</h4>
                    <h4>Let us know what you thinking</h4>
                    
                </div>
                <span class="error"></span>
                <form class="comment-form mt-2" method="POST" action="{{route('comments.store')}}">
                    <input type="hidden" name="_token" id="_token" value="{{Session::token()}}">
                    <textarea name="comment" id="comment"></textarea>
                    <span class="comment_error text-danger"></span>
                    <input type="hidden" name="post_id" id="post_id" class="mt-1" value="{{$post->id}}">
                    <button type="submit" class="btn btn-lg btn-success mt-4 float-right">Share</button>
                </form>
            </div>
            
            @else
            <div class="blocked-box p-3 text-center">
                <h5>Ouch! It appears you have been stripped off some priviledges. </h5>
                <h5>Due to this, you cannot share your opinion on this topic</h5>
                <small class="text-muted">For more details on why this is happening <i class="fa fa-arrow-right"></i> <a href="">here</a></small>
            </div>
            
            @endif
            
            
        </div>
        
        <!--Start of right col-3 -->
        <div class="col-lg-3 col-md-3 ml-3">
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
        
        CKEDITOR.replace('comment');
        
        var  $ = jQuery.noConflict();
        $(document).ready(function()
        {  
            getAuthUserSelectedCategories();
            
            function getAuthUserSelectedCategories()
            {
                let user_id = "{{Auth::user()->id}}";
                let _token = "{{csrf_token()}}";
                $.ajax({
                    url:"{{route('user_choice')}}",
                    type:"GET",
                    data:{action:'get_selected_categories'},
                    success:function(result)
                    {
                        if(result.success)
                        {
                            $('.sidenav').html(result.data);
                        }
                    }
                });
                
            }
            
            
            $(".comment-form").submit(function(event){
                event.preventDefault();
                var ckeditor = CKEDITOR.instances.comment.getData();
                
                if(ckeditor == "")
                {
                    $(".comment_error").text("Please enter something!");
                }
                else{
                    var post_id = $("#post_id").val();
                    var _token = $("#_token").val();
                    $.ajax({
                        url : $(this).attr("action"),
                        type : $(this).attr("method"),
                        data : {comment:ckeditor,post_id:post_id,_token:_token},
                        dataType:"json",
                        success:function(result){
                            if(result.success)
                            {
                                window.location.reload();
                                ckeditor= " ";
                            }
                            else{
                                (".error").html('<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Action Failed!</strong> Your comment could not be submitted. Something is not right. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')  ;
                            }
                        }
                        
                    });
                }
            });
            
            
            
        });
        
        $('.fa-heart-o').click(function(){
            var comment_id = $(this).attr('data-id');
            if($(this).hasClass('like'))
            {
                $(this).removeClass('like');
                $(this).removeClass('text-primary');
                $(this).addClass('liked');
                $(this).addClass('text-danger');
            }
            else if($(this).hasClass('liked')){
                $(this).removeClass('liked');
                $(this).removeClass('text-danger');
                $(this).addClass('like');
                $(this).addClass('text-primary');
            }
            
            axios.post("/like/"+comment_id,{
                _token:_token
            }).then(function(response){
                
                if(response.data.success){
                    console.log(response.data.success);
                }
            }).catch(function(error){
                console.log(error);
            });
        });
        
        
        
    </script>
    
    @endsection
    
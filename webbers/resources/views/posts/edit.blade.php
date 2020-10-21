@extends('layouts.app')
@section('css')
<script src="{{asset('ckeditor/ckeditor.js')}}"></script>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row main-row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="sidenav">
            </div>
        </div>
        
        <div class="col-lg-lg-7 col-md-7">
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{session('error')}}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            
            <form method="POST" action="{{route('questions.update',["identifier" => $post->identifier])}}" id="askForm">
                @csrf
                @method('PATCH')
                <input type="hidden" name="identifier" value="{{$post->identifier}}">
                <div class="form-group">
                    <label class="label">Title of your question/post <i>(Be concise about your title)</i></label>
                    <input type="text" name="post_title"
                    class="form-control form-control-sm @error('post_title') is-invalid @enderror" value="{{$post->post_title}}"
                    placeholder="For instance: How to retrieve retrieve form field values">
                    @error('post_title')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="label">Content of your question <i>(Your content must be unambiguous)</i></label>
                    <textarea name="post_content" class="@error('post_content') is-invalid @enderror" id="post_content">{{$post->post_body}}</textarea>
                    @error('post_content')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="label">Under which category would you like to place this post? <span style="cursor: pointer"
                        class="text-primary ml-2" data-toggle="modal" data-target="#tagModal"><small>View Categories</small></span></label>
                        <input type="text" name="post_tag" class="form-control form-control-sm @error('post_tag') is-invalid @enderror"
                        value="{{$post->post_category}}">
                        @error('post_tag')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                        
                        @if(session('error_tag'))
                        <small class="text-danger">{{session('error_tag')}}}</small>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label class="label">Would you like to place a bounty on this post? <i>(optional)</i></label>
                        <input type="number" name="bounty" class="form-control form-control-sm" value="{{old('bounty')}}">
                        @if(session('error_bounty'))
                        <small class="text-danger">{{session('error_bounty')}}}</small>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn btn-md btn-success float-right">Post Question</button>
                    
                </form>
                
            </div>
            
            <!--Start of right col-3 -->
            <div class="col-lg-3 col-md-3">
                <div class="articles bg-white p-2">
                    <h6>Recommended Articles</h6>
                    <div class="article p-2 mb-2 bg-white border-top border-bottom border-right" style="border-left: 3px solid #82E0AA">
                        <div class="author d-flex">
                            <a href="" class="text-dark">
                                <img src="https://www.gravatar.com/avatar/5a753a7da00760f1b7a2d94fbfda7b45.jpg?d=identicon&s=150&r=pg" width="30"> Paulo Henrique S.S.</a>
                            </div>
                            <div class="article-title mt-1" style="font-size: 12px">
                                <a href="https://www.codeproject.com/Articles/1029482/A-Beginners-Tutorial-for-Understanding-and-Imple-2"> A Beginner's Tutorial for Understanding and Implementing a CRUD APP USING Elasticsearch and C# - Part 1</a>
                            </div>
                        </div>
                        
                        <div class="article p-2 bg-white border-top border-bottom border-right" style="border-left: 3px solid #82E0AA">
                            <div class="author d-flex">
                                <a href="" class="text-dark"><img src="https://www.gravatar.com/avatar/6e66bbbd727d279fe5237f6e11b0a5a0.jpg?d=identicon&s=150&r=pg
                                    " width="30"> Łukasz Bownik</a>
                                </div>
                                <div class="article-title mt-1" style="font-size: 12px">
                                    <a href="https://www.codeproject.com/Articles/5061258/The-Psychological-Reasons-for-Software-Project-Fai">The Psychological Reasons for Software Project Failures</a>
                                </div>
                            </div>
                            
                            <div class="mt-2 p-2 text-center">
                                <h6>Do you want to create your own article?</h6>
                                <a href="" class="btn btn-md article-btn text-white shadow-sm"
                                style="background: #F1948A; border-radius:50px">Start Here <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!--End of right col-3 -->
                </div>
            </div>
            @endsection
            
            @section('scripts')
            <script>
                CKEDITOR.replace('post_content');
                $(document).ready(function() {
                    getAuthUserCategories();
                    
                    function getAuthUserCategories() {
                        let user_id = "{{Auth::user()->id}}";
                        let _token = "{{csrf_token()}}";
                        $.ajax({
                            url: "{{route('user_choice')}}",
                            type: "GET",
                            data: {
                                action: 'get_selected_categories'
                            },
                            success: function(result) {
                                if (result.success) {
                                    $('.sidenav').html(result.data);
                                }
                            }
                        });
                    }
                });
            </script>
            
            
            @endsection
            
            
            
            
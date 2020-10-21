@extends('layouts.app')
@section('css')
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row main-row">
        <div class="col-lg-2 col-md-2 col-sm-2">
            <div class="sidenav">
                <a href="#about">About</a>
                <a href="#services">Services</a>
                <a href="#clients">Clients</a>
                <a href="#contact">Contact</a>
                <button class="dropdown-btn">Dropdown 
                    <i class="fa fa-caret-down"></i>
                </button>
                <div class="dropdown-container">
                    <a href="#">Link 1</a>
                    <a href="#">Link 2</a>
                    <a href="#">Link 3</a>
                </div>
                <a href="#contact">Search</a>
            </div>
        </div>
        
        <div class="col-lg-lg-9 col-md-9">
            <div class="p-2 m-2">
                <a href="" class="btn btn-sm btn-primary ask-btn">
                    <i class="fa fa-plus"></i> Ask Question
                </a>
            </div>
            
            <div class="row">
                <div class="col-lg-9">
                    @if(Auth::user()->setting->ushured == 0)
                    
                    <div class="alert alert-light alert-dismissible" role="alert" style="border-left: 3px solid #ABEBC6">
                        <h4 class="alert-heading">Kudos!</h4>
                        <p>
                            Aww yeah <a href="">{{'@'.Auth::user()->username}}</a>, you have successfully completed the signup process. You are officially a <b>webber :)</b>. However, as part of our measures to ensuring the smooth navigation of this platform for users like yourself,
                            we have a code of conduct that we would like you to take a good look at. Check it out 
                            <a href="">here
                            </a>
                        </p>
                        <button type="button" class="close ushur" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                </div>
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
                    </div>
                </div>
            </div>
        </div>
        @endsection
        
        @section('scripts')
        
        <script>
            /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
            var dropdown = document.getElementsByClassName("dropdown-btn");
            var i;
            
            for (i = 0; i < dropdown.length; i++) {
                dropdown[i].addEventListener("click", function() {
                    this.classList.toggle("active");
                    var dropdownContent = this.nextElementSibling;
                    if (dropdownContent.style.display === "block") {
                        dropdownContent.style.display = "none";
                    } else {
                        dropdownContent.style.display = "block";
                    }
                });
            }
            
            
        </script>
        
        @endsection
        
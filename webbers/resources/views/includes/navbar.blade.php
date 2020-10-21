<nav class="navbar navbar-expand-lg main-navbar" style="background: #45b39d">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">{{config('app.name')}}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" 
        aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item ml-4">
                <a class="nav-link home text-white" href="{{route('home')}}">Home</a>
            </li>
            @auth
            <li class="nav-item ml-4" style="margin-top: 12px">
                <div class="dropdown">
                    <a id="navbarDropdown" class="dropdown-toggle text-white" href="#" role="button" 
                    data-toggle="dropdown" 
                    aria-haspopup="true" aria-expanded="false" v-pre>
                    Filter By
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item filter" href="{{route('home',['filter_by'=>'unanswered'])}}"><span class="fa fa-ellipsis-h"></span> Unanswered</a>
                    <a class="dropdown-item filter" href="{{route('home',['filter_by'=>'answered'])}}"><span class="fa fa-check"></span> Answered</a>
                    <a class="dropdown-item filter" href="{{route('home',['filter_by'=>'bountied'])}}"><span class="fa fa-money"></span> Bountied</a>
                    <a class="dropdown-item filter" href="{{route('home',['filter_by'=>'hidden'])}}"><span class="fa fa-eye-slash"></span> Hidden</a>
                    
                    <form id="logout-form" action="{{route('logout')}}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </li>
        
        <li class="nav-item" style="margin-top: 9px; margin-left:30px">
            <a href="{{route('questions.create')}}" class="btn btn-sm  ask-btn text-dark border-0 mb-1">
                <i class="fa fa-plus"></i> Ask Question
            </a>
        </li>  
        @endauth
    </ul>
    
    <form class="form-inline">
        <input class="form-control form-control-sm border-0 search" type="search" placeholder="Search"
        aria-label="Search">
    </form>
    <!-- Authentication Links -->
    @guest
    <a class="btn btn-md btn-info signin border-0 ml-2 shadow-sm text-white" href="{{route('login')}}">
        {{ __('Sign in') }}
    </a>
    @if (Route::has('register'))
    <a class="btn btn-md signup btn-warning border-0 ml-2 shadow-sm text-white" href="{{route('register')}}">
        {{ __('Sign up') }}
    </a>
    @endif
    @else
    <a href="">
        <i class="fa fa-bell text-white" style="margin-right: 20px; font-size:18px"></i>
    </a>
    
    
    <a  class="text-white" href="#" role="button" data-toggle="dropdown" 
    aria-haspopup="true" aria-expanded="false" v-pre>
    <img src="{{getAvatar(Auth::user()->id)}}" width="25px" class="img rounded-circle">
    <small>{{Auth::user()->username}}</small>
</a>
<a href="" class="text-white ml-4">
    <i class="fa fa-cog"></i>
</a>
@endguest
</div>
</div>
</nav>
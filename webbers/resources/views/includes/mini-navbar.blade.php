<ul class="nav mini-nav p-2 justify-content-left">
    <li class="nav-item">
        <a class="nav-link border-0 @if($active == 'unanswered') active shadow-sm @endif"
        href="{{route('questions',['q' => 'unanswered'])}}">Unanswered</a>
    </li>
    <li class="nav-item">
        <a class="nav-link border-0" href="{{route('questions',['q' => 'answered'])}}"> Answered</a>
    </li>
    <li class="nav-item">
        <a class="nav-link border-0" href="#">Trending</a>
    </li>
    <li class="nav-item">
        <a class="nav-link border-0" href="#">Discusions</a>
    </li>
    
    @if(Auth::user())
    <li class="nav-item">
        <a class="nav-link border-0" href="#">My Categories</a>
    </li>
    @endif
    
</ul>
<div class="sidenav bg-white">
    @if(getUserCategories(Auth::id())->count() > 0)
    <h6 class="ml-3">Followed Categories</h6>
    @foreach(getUserCategories(Auth::id()) as $category)
    <a href="{{route("home", ["tag" => $category->category_name])}}" class="category-item">
        <i class="fa fa-circle" style="color:{{color()}}"></i> 
        {{ucfirst($category->category_name)}} 
        <span class="fa fa-ellipsis-h float-right mt-1" style="display:none"></span>
    </a> 
    @endforeach
    @if(getUserCategoriesCount(Auth::id()) > 12)
    <div class="text-center">
        <a href="" class="text-muted"><small>See All</small></a>
    </div>
    @endif
    @endif
</div>
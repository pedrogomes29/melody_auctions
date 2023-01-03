<h2 class="ms-1 mb-4">Categories</h2>
<div id="categories-carousel" class="carousel slide  m-auto" data-bs-ride="true">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#categories-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Category 1"></button>
        @for ($i = 1; $i < sizeof($categories); $i++)
            <button type="button" data-bs-target="#categories-carousel" data-bs-slide-to="{{$i}}" aria-label="Category {{$i+1}}"></button>
        @endfor
    </div>
    <div class="carousel-inner h-100">
        @foreach($categories as $category)
            <div class="carousel-item h-100 {{$loop->first?'active':''}}">
                <img src="{{$category->photo?asset($category->photo):asset('images/default-category.png')}}" class="d-block img-fluid" alt="...">
                <h1 id="category-{{$category->id}}" class="category" >{{$category->name}}</h1>
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#categories-carousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#categories-carousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
  
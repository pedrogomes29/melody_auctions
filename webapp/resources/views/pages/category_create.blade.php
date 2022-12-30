@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
@endsection

@section('content')
  <article class='container'>
    <header class="border-bottom pb-4">
      <h1> Create a new Category</h1>
    </header>

    <main>

      <form class="form-create" method="post" action="{{ route('category.store')}}" enctype="multipart/form-data">


        <div class="mb-3">
          <label for="category_name" class="form-label">Category Name</label>
          <input required name="name" type="text" class="form-control " id="category_name">
        </div>

        <div class="mb-3">
            <label for="category_image" class="form-label ">Category Image</label>
            <input required name="photo" class="form-control" type="file" id="category_image" accept="image/png, image/jpeg">
          </div>

        {{ csrf_field() }}
        <div class="container">
            <div class="text-center">
                <button type="submit"  class="btn btn-primary">Submit</button>
            </div>
        </div>
      </form>
    </main>

  </article>
@endsection

@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
@endsection

@section('content')
  <article class='container'>
    <header class="border-bottom pb-4">
      <h1> Update Category</h1>
    </header>

    <main>

      <form class="form-create mb-5 p-5" method="post" action="{{ route('category.update', ['id' => $id])}}" enctype="multipart/form-data">
        @method('PUT')
        <div class="mb-3">
          <label for="category_name" class="form-label">Category Name</label>
          <input required name="name" type="text" class="form-control" value="{{$name}}"id="category_name">
        </div>

        <div class="mb-3">
            <label for="category_image" class="form-label ">New Image (not required)</label>
            <input name="photo" class="form-control" type="file" id="category_image" accept="image/png, image/jpeg">
          </div>

        {{ csrf_field() }}
        <div class="container">
            <div class="text-center">
                <button type="submit"  class="btn btn-primary">Submit</button>
            </div>
        </div>
      </form>

        <div class="container">
            <div class="text-center">
                <form method="post" action ="{{ route('category.destroy', ['id' => $id]) }}" class="mt-5">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <button type="submit"  class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>



    </main>

  </article>
@endsection

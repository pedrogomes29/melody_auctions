@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
@endsection

@section('content')
  <article class='container'>
    <header class="border-bottom pb-4">
      <h1> Create new Auction</h1>
    </header>

    <main>

      <form class="form-create" method="post" action="{{ url('auction') }}" onsubmit="return form_create_auction(this)" enctype="multipart/form-data">

        <div class="mb-3 img-preview-container">
          <label for="auction_image" class="form-label ">Auction image</label>
          <img id="auction_img" src="{{url('/images/default_auction.jpg')}}" class="img-fluid mx-auto d-block " alt="...">
          <br>
          <input required name="photo" class="form-control" type="file" id="auction_image" accept="image/png, image/jpeg">
        </div>

        <div class="mb-3">
          <label for="auction_name" class="form-label">Auction Name (Title)</label>
          <input required name="name" type="text" class="form-control " id="auction_name">
        </div>
        <div class="mb-3">
          <label for="auction_description" class="form-label">Description</label>
          <textarea required name="description" class="form-control" id="auction_description" style="height:100%;" rows="5"></textarea>
        </div>

        
        <div class="mb-3">
          <label for="manufactor" class="form-label">Manufactor</label>
          <input required name="manufactor" class="form-control" list="manufactorOptions" id="manufactor" placeholder="Type to search...">
          <datalist id="manufactorOptions">
            @foreach ($manufactors as $manufactor)
              <option value="{{ $manufactor->name }}">
            @endforeach
              
          </datalist>
        </div>

        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <select required name="category" id="category" class="form-select" aria-label="Default select example">
            @foreach ($categories as $category)
              <option value="{{ $category->id }}">
                {{ $category->name }}
              </option>
            @endforeach
          </select>
          @if ($errors->has('category_error'))
            <div class="alert alert-danger" role="alert">
              {{ $errors->first('category_error') }}
            </div>
          @endif
        </div>

        <div class="mb-3">
          <label for="startDate">Start Date</label>
          <input required name="startdate" id="startDate" class="form-control w-auto" type="datetime-local" />
          @if ($errors->has('startdate_error'))
            <div class="alert alert-danger" role="alert">
              {{ $errors->first('startdate_error') }}
            </div>
          @endif
        </div>

        <div class="mb-3">
          <label for="endDate">End Date</label>
          <input required name="enddate" id="endDate" class="form-control w-auto" type="datetime-local" />
          @if ($errors->has('enddate_error'))
            <div class="alert alert-danger" role="alert">
              {{ $errors->first('enddate_error') }}
            </div>
          @endif
        </div>
        
        <div class="mb-3">
          <label for="start_value" class="d-block">Start Value</label>

          <div class="input-group mb-3">
            <span class="input-group-text">€</span>
            <input required name="startvalue" type="number" step="0.01" min="0.01" value ="0" class="form-control" name="start_value" aria-label="Euro amount (with dot and two decimal places)">
          </div> 
          
        </div>

          
        <div class="mb-3">
          <label for="minbiddiff" class="d-block">Min Bid difference</label>

          <div class="input-group mb-3">
            <span class="input-group-text">€</span>
            <input required name="minbiddiff" type="number" step="0.01" min="0.01" value ="0" class="form-control" name="minbiddiff" aria-label="Euro amount (with dot and two decimal places)">
          </div>
        </div>

        {{ csrf_field() }}
        <button type="submit"  class="btn btn-primary">Submit</button>
      </form>

      

    </main>

  </article>
@endsection

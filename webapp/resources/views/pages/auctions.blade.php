@extends('layouts.app')

@section('title', 'Auctions')
@section('scripts')
    <script type="text/javascript" src={{ asset('js/auctions.js') }} defer> </script>
@endsection

@section('searchContent',$search)

@section('content')
    <div class="d-flex flex-row">
        <div class="d-flex flex-column flex-shrink-0 p-3 border-right border-dark">
            <div id="category"class="dropdown">
                <p>Category</p>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">Any category</button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                    @foreach($categories as $category)
                        <li><p class="dropdown-item" id="category-{{$category->id}}">{{$category->name}}</p></li>
                    @endforeach
                </ul>
            </div>
            <div id="ongoing" class="dropdown">
                <p>Type</p>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">None selected</button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                <li><p class="dropdown-item">Active</p></li>
                <li><p class="dropdown-item">Recent</p></li>
                </ul>
            </div>
        </div>
        <div id='auctions'>
            @include('partials.auctions', ['auctions' => $auctions])
        </div>
    </div>
    <footer id='auctions-count'>{{$nrAuctions}} results</footer>
@endsection

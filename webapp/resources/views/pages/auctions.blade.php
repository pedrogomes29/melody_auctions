@extends('layouts.app')

@section('title', 'Auctions')
@section('scripts')
    <script type="text/javascript" src={{ asset('js/auctions.js') }} defer> </script>
@endsection

@section('styles')
    <link href="{{ asset('css/auctions.css') }}" rel="stylesheet">
@endsection

@section('searchContent',$search)

@section('content')
    <div class="d-flex flex-row">
        <div class="d-flex flex-column flex-shrink-0 p-3 px-5 me-5 border-right border-dark position-sticky">
            <div id="category"class="dropdown my-3">
                <h1>Category</h1>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">{{$categoryName}}</button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                    @foreach($categories as $category)
                        <li><p class="dropdown-item {{$categoryId==$category->id?'chosen':''}}" id="category-{{$category->id}}">{{$category->name}}</p></li>
                    @endforeach
                </ul>
            </div>
            <div id="ongoing" class="dropdown my-3">
                <h1 >Type</h1>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">{{$onGoingText}}</button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                    <li><p class="dropdown-item {{$onGoing==='1'?'chosen':''}}">Active</p></li>
                    <li><p class="dropdown-item {{$onGoing==='0'?'chosen':''}}">Closed</p></li>
                </ul>
            </div>
        </div>
        <div id='auctions' class='flex-grow-1'>
            <h1>{{$nrAuctions}} results</h1>
            @include('partials.auctions', ['auctions' => $auctions,'nrAuctions'=>$nrAuctions,'offset'=>$offset])
        </div>
    </div>
@endsection

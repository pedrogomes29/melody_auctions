@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection


@section('content')
    <div class="container">
        <form action="/admin/{{$adminId}}/auctions/{{$auction->id}}" method="POST">
            {{ csrf_field() }}
            @method('PUT')
            <div class="form-group">
            <label for="auctionName">Auction Name</label>
            <input name="name" id="auctionName" type="text" class="form-control"  value="{{$auction->name}}">
            </div>
            <div class="form-group">
            <label for="auctionDescription">Auction Description</label>
            <textarea  name="description" id="auctionDescription" cols="30" rows="10" >{{$auction->description}}</textarea> 
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>        
        </form>
        <form action="/admin/{{$adminId}}/auctions/{{$auction->id}}" method="POST">
            {{ csrf_field() }}
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
@endsection
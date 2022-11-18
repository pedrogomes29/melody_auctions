@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/edit_auctions.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
        @endforeach
        @if (isset($success))
            @if (!$success)
                <div class="alert alert-danger" role="alert">
                    Invalid dates!
                </div>
            @endif
        @endif
        <form id="store" action="/auction/{{$auction->id}}/store" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="text-center">
                @if (File::exists(public_path().'/storage/auctions/'.$auction->photo))
                    <img class="img-thumbnail " src="{{ asset('storage/auctions/' . $auction->photo) }}" alt="Auction Photo">
                @else
                    <img class="img-thumbnail " src="{{ asset('default_image/default_auction.jpg') }}" alt="Auction Photo">
                @endif
            </div>
            <label for="photo">Add image</label>
            <input type="file" name="photo" id="photo" class="form-control-file">
            <div class="text-center">
                <button type="submit" class="btn btn-primary upload-btn">Upload</button>
            </div>    
        </form>
        <form id="update" action="/auction/{{$auction->id}}/edit" method="POST">
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
            <div class="form-group">
                <label for="minBidDif">Minimum Bid Difference</label>
                <input name="minBidDif" id="minBidDif" type="number" min="0.01" step="0.01" class="form-control"  value="{{$auction->minbidsdif}}">
                </div>
            <div class="form-group">
                <label for="auctionStart">Auction Start Date</label>
                <input name="startDate" id="auctionStart" type="datetime-local" class="form-control" value="{{$auction->startdate}}">
            </div>
            <div class="form-group">
                <label for="auctionStart">Auction End Date</label>
                <input name="endDate" id="auctionEnd" type="datetime-local" class="form-control" value="{{$auction->enddate}}">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </form>
        <form id="delete" action="/auction/{{$auction->id}}/edit" method="POST">
            {{ csrf_field() }}
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
@endsection

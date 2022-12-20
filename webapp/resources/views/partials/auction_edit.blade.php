
@section('styles')
    <link href="{{ asset('css/edit_auctions.css') }}" rel="stylesheet">
@endsection

<div class="container" id="edit_auctions">
    <div class="alert alert-danger" id = "popupError" style="display: none"  role="alert"></div>
    @if (!$admin)
    <form id="store" action="/auction/{{$auction->id}}/updatePhoto" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="img-preview-container">
            <div class="text-center ">
                @if ($auction->photo!='')
                    <img class="img-thumbnail " src="{{ asset($auction->photo) }}" alt="Auction Photo">
                @else
                    <img class="img-thumbnail " src="{{ asset('default_images/default_auction.jpg') }}" alt="Auction Photo">
                @endif
            </div>
            <label for="photo">Add image</label>
            <input required type="file" name="photo" id="photo" class="form-control-file">
            <div class="text-center">
                <input type="submit" class="btn btn-primary upload-btn" value ="Upload">
            </div>   
        </div> 
    </form>
    @else 
        <div class="text-center ">
            @if ($auction->photo!='')
                <img class="img-thumbnail " src="{{ asset($auction->photo) }}" alt="Auction Photo">
            @else
                <img class="img-thumbnail " src="{{ asset('default_images/default_auction.jpg') }}" alt="Auction Photo">
            @endif
        </div>
        <div class="text-center">
            <button data-csrf="{{csrf_token()}}" onclick="setDefaultImage(this)" id="set_default_image" type="submit" class="btn btn-warning upload-btn" data-auction="/auction/{{$auction->id}}/defaultImage">Set Default Image</button>  
        </div>
    @endif
    @if ($admin)
        <form id="adminUpdate" action="/auction/{{$auction->id}}/admin"  method="POST">
    @else 
        <form id="update" action="/auction/{{$auction->id}}"  method="POST">
    @endif 
        {{ csrf_field() }}
        @method('PUT')
        <div class="form-group">
        <label for="auctionName">Auction Name</label>
        <input required name="name" id="auctionName" type="text" class="form-control"  value="{{$auction->name}}">
        </div>
        <div class="form-group">
        <label for="auctionDescription">Auction Description</label>
        <textarea  name="description" id="auctionDescription" cols="30" rows="10" >{{$auction->description}}</textarea> 
        </div>
        @if (!$admin)
        <div class="form-group">
            <label for="minBidDif">Minimum Bid Difference</label>
            <input required name="minBidDif" id="minBidDif" type="number" min="0.01" step="0.01" class="form-control"  value="{{$auction->minbidsdif}}">
            </div>
        <div class="form-group">
            <label for="auctionStart">Auction Start Date</label>
            <input required name="startDate" id="auctionStart" type="datetime-local" class="form-control" value="{{$auction->startdate}}">
        </div> 
        <div class="form-group">
            <label for="auctionStart">Auction End Date</label>
            <input required name="endDate" id="auctionEnd" type="datetime-local" class="form-control" value="{{$auction->enddate}}">
        </div>
        @endif
    </form>
    
</div>

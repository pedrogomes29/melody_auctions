
<div class="modal fade" id="{{$POPUP_ID}}" tabindex="-1" aria-labelledby="{{$POPUP_TITLE_ID}}" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{$POPUP_TITLE_ID}}">{{$POPUP_TITLE}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @include('partials.auction_edit', ['auction' => $auction, 'admin' => Auth::guard('admin')->user()])
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        
        @if (Auth::guard('admin')->user())
        <button data-csrf="{{csrf_token()}}" data-auction="/auction/{{$auction->id}}/admin" onclick="adminDeleteAuction(this)" class="btn btn-danger">Delete</button>   @else 
        <button data-csrf="{{csrf_token()}}" data-auction="/auction/{{$auction->id}}" onclick="deleteAuction(this)" class="btn btn-danger">Delete</button>
        @endif 

        <div class="text-center">
          @if (Auth::guard('admin')->user())
            <button type="submit" onclick="adminUpdateAuction(this)" class="btn btn-warning">Update</button>
          @else
            <button type="submit" onclick="updateAuction(this)" class="btn btn-warning">Update</button>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
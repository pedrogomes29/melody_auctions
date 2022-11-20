<a href="#" class="list-group-item list-group-item-action " aria-current="true">
  <div class="d-flex w-100 justify-content-between">
  
    <h5 class="mb-1">{{$bid->bidder->firstname . ' '. $bid->bidder->lastname }}</h5>
    <small>{{$bid->bidsdate}}</small>
  </div>
  <p class="mb-1">{{$bid->value}}€</p>
</a>

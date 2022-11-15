<h1>{{$auction->productname}}</h1>
<p>Time left: {{$auction->timeleft}}</p>
<p>Price: {{$auction->minbid}}</p>
@if($auction->photo!==" ")
    <img height=30px src={{ asset('images/' . $auction->photo)}}></img>
@else
    <img height=30px src={{ asset('images/auction_default.svg' )}}></img>
@endif

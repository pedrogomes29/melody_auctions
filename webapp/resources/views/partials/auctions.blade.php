<div class="row row-cols-1 row-cols-md-3 g-4 flex-grow-1">
    @each('partials.auction',$auctions,'auction')
</div>

@if(isset($offset) && isset($nrAuctions))
    <div id="auctions-footer">
    @if(($offset+1)*10<$nrAuctions)
        <button id="load-more" type="button">Load more</button>
    @endif
        <footer class="ms-4 h3 mt-5">Showing {{min(($offset+1)*10,$nrAuctions)}} of {{$nrAuctions}} results</footer>
    </div>
@endif
<div class="d-flex w-100 auctions-list flex-wrap">

    <article class="auction-card card " >

        <img class="image" src="https://electromusica.pt/tyshumpu/2021/06/saxofone-alto-gara.png');"/>

        <section class="auction-card-text">
            <h1 class=" title ">Saxofone</h1>

            <p class="text-secondary price-text">Current Bid:</p>
            <p class="price"><span class='price-value'>100 </span> €</p>
            <p class="text-secondary date">Ends in: 10m 25s</p>
        <section>
    </article>

</div>

@if(isset($offset) && isset($nrAuctions))
    <div id="auctions-footer">
    @if(($offset+1)*10<$nrAuctions)
        <button id="load-more" type="button">Load more</button>
    @endif
        <footer class="ms-4 h3 mt-5">Showing {{min(($offset+1)*10,$nrAuctions)}} of {{$nrAuctions}} results</footer>
    </div>
@endif
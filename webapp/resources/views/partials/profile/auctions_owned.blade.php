<section id='owned-auctions' class="container-fluid">
    <h1>Auctions owned</h1>
    <div class="ms-1">  
            @include('partials.auctions', ['auctions' => $auctions])
    </div>
    <button id="owned-button" type="button" class="ms-5 mt-5 btn btn-dark btn-rounded">See all auctions owned</button>
</section>
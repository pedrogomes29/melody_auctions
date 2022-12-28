
<section class="auctions-followed">
    <h1 class="title"> {{sizeof($auctions).' '}}Auctions followed</h1>
    <section class="auctions-list">
        @include('partials.auctions', ['auctions' => $auctions])   
</section>

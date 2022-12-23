<section id ="bids">

    <h2>My Bids</h2>
    <table class="table table-hover fs-5 ">
        <thead>
            <tr>
                <th scope="col">Auction</th>
                <th scope="col">Date</th>
                <th scope="col">Value</th>
            </tr>
        </thead>
        <tbody>
            {{$bids = $user->bids()}}
            @foreach($bids as $bid)
                <tr>  
                    <th scope="row" class="mb-1">
                        <a href="{{ route('auction.show', $bid->auction->id) }}" class="link-dark text-decoration-none">
                            {{$bid->auction->name }}
                        </a>
                    </th>
                    <td class="bid-date">{{$bid->bidsdate}}</td>
                    <td >{{$bid->value}}€</td>
                </tr>
            @endforeach
        </tbody>

    
    </table>
    {{$bids->fragment('bids')->appends($_GET)->links()}}

    
</section>
<div class="d-flex w-100 auctions-list flex-wrap">

    <div class="auction-card card    " >

    <img class="image" src="https://electromusica.pt/tyshumpu/2021/06/saxofone-alto-gara.png');"/>
        <div class="auction_info">
            <span class="auction_state color-red">Closed</span>
            <p class="card-text font-weight-bold h5 ">Ended 12/30/2002</p>
        </div>
        
        <h3 class=" title text-white text-shadow-1  mt-auto  display-6 lh-1 fw-bold">Saxofone</h3>
        
        <p class="text font-weight-bold h5 ">Sold for: 100€</p>
    </div>

    <div class="auction-card card card-cover  overflow-hidden text-bg-dark rounded-4 shadow-lg" style="
    background-image:linear-gradient(var(--gradient), var(--gradient)), 
                    url('https://electromusica.pt/tyshumpu/2021/06/saxofone-alto-gara.png');">
        <div class="auction_info">
            <span class="auction_state color-green">Open</span>
            <p class="card-text font-weight-bold h5 ">Ends in 00:00:00 s</p>
        </div>
        
        <h3 class=" title text-white text-shadow-1  mt-auto  display-6 lh-1 fw-bold">Saxofone</h3>
        
        <ul class="">
            <li class="me-auto">
                <p class="card-text font-weight-bold h5 ">Current Price: 11.1€</p>
            </li>    
        </ul>
    </div>

    <div class="auction-card card card-cover  overflow-hidden text-bg-dark rounded-4 shadow-lg" style="
    background-image:linear-gradient(var(--gradient), var(--gradient)), 
                    url('https://electromusica.pt/tyshumpu/2021/06/saxofone-alto-gara.png');">
        <div class="auction_info">
            <span class="auction_state color-yellow">Not Started Yet</span>
            <p class="card-text font-weight-bold h5 ">Starts in 10 min</p>
        </div>
        
        <h3 class=" title text-white text-shadow-1  mt-auto  display-6 lh-1 fw-bold">Saxofone</h3>
        
        <ul class="">
            <li class="me-auto">
                <p class="card-text font-weight-bold h5 ">Start Price: 50€</p>
            </li>    
        </ul>
    </div>


    <div class="auction-card card card-cover  overflow-hidden text-bg-dark rounded-4 shadow-lg" style="
    background-image:linear-gradient(var(--gradient), var(--gradient)), 
                    url('https://electromusica.pt/tyshumpu/2021/06/saxofone-alto-gara.png');">
        <div class="auction_info">
            <span class="auction_state color-red">Closed</span>
            <p class="card-text font-weight-bold h5 ">Ended 12/30/2002</p>
        </div>
        
        <h3 class=" title text-white text-shadow-1  mt-auto  display-6 lh-1 fw-bold">Saxofone</h3>
        
        <ul class="">
            <li class="me-auto">
                <p class="card-text font-weight-bold h5 ">Sold for: 100€</p>
            </li>    
        </ul>
    </div>


    <div class="auction-card card card-cover  overflow-hidden text-bg-dark rounded-4 shadow-lg" style="
    background-image:linear-gradient(var(--gradient), var(--gradient)), 
                    url('https://electromusica.pt/tyshumpu/2021/06/saxofone-alto-gara.png');">
        <div class="auction_info">
            <span class="auction_state color-red">Closed</span>
            <p class="card-text font-weight-bold h5 ">Ended 12/30/2002</p>
        </div>
        
        <h3 class=" title text-white text-shadow-1  mt-auto  display-6 lh-1 fw-bold">Saxofone</h3>
        
        <ul class="">
            <li class="me-auto">
                <p class="card-text font-weight-bold h5 ">Sold for: 100€</p>
            </li>    
        </ul>
    </div>


    <div class="auction-card card card-cover  overflow-hidden text-bg-dark rounded-4 shadow-lg" style="
    background-image:linear-gradient(var(--gradient), var(--gradient)), 
                    url('https://electromusica.pt/tyshumpu/2021/06/saxofone-alto-gara.png');">
        <div class="auction_info">
            <span class="auction_state color-red">Closed</span>
            <p class="card-text font-weight-bold h5 ">Ended 12/30/2002</p>
        </div>
        
        <h3 class=" title text-white text-shadow-1  mt-auto  display-6 lh-1 fw-bold">Saxofone</h3>
        
        <ul class="">
            <li class="me-auto">
                <p class="card-text font-weight-bold h5 ">Sold for: 100€</p>
            </li>    
        </ul>
    </div>

    
    <div class="auction-card card card-cover  overflow-hidden text-bg-dark rounded-4 shadow-lg" style="
    background-image:linear-gradient(var(--gradient), var(--gradient)), 
                    url('https://electromusica.pt/tyshumpu/2021/06/saxofone-alto-gara.png');">
        <div class="auction_info">
            <span class="auction_state color-red">Closed</span>
            <p class="card-text font-weight-bold h5 ">Ended 12/30/2002</p>
        </div>
        
        <h3 class=" title text-white text-shadow-1  mt-auto  display-6 lh-1 fw-bold">Saxofone</h3>
        
        <ul class="">
            <li class="me-auto">
                <p class="card-text font-weight-bold h5 ">Sold for: 100€</p>
            </li>    
        </ul>
    </div>

</div>

@if(isset($offset) && isset($nrAuctions))
    <div id="auctions-footer">
    @if(($offset+1)*10<$nrAuctions)
        <button id="load-more" type="button">Load more</button>
    @endif
        <footer class="ms-4 h3 mt-5">Showing {{min(($offset+1)*10,$nrAuctions)}} of {{$nrAuctions}} results</footer>
    </div>
@endif
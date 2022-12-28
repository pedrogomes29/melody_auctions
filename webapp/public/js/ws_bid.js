

var pusher = new Pusher('13f0cde651cf59e898d7', {
  cluster: 'eu',
});

var channel = pusher.subscribe('bids-channel');
channel.bind('new_bid-event', updateBid);




function updateBid(bid) {
    bid = bid.bid;
    bidder = bid.bidder;
    console.log(bid);
    const currentPrice = document.querySelector("#current_price");
    currentPrice.innerHTML = bid.value + ' €'; 
    const lastBidder = document.querySelector("#last_bidder");
    lastBidder.innerHTML = '<a href="'+ '/user/'+ bidder.username + '">'+ bidder.firstname + ' ' + bidder.lastname + '</a>';

    const load_bids_bt = document.querySelector("#load_bids");
    load_bids_bt.setAttribute("data-offset", 0);

    const bidding_history = document.querySelector("#bidding_history");
    bidding_history.innerHTML = '';
    load_bids_bt.click();


}
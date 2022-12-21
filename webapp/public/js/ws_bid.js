

var pusher = new Pusher('13f0cde651cf59e898d7', {
  cluster: 'eu',
});

var channel = pusher.subscribe('bids-channel');
channel.bind('new_bid-event', newBidNotification);


function newBidNotification(bid) {
    console.log(JSON.stringify(bid));
}

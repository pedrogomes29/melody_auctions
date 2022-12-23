function convert_bids_localtime(){

    let bids = document.querySelectorAll('.bid-date');

    for (var i = 0; i < bids.length; i++) {
        let bid_date = bids[i];        
        // convert bid time to local time
        const date = new Date(bid_date.innerHTML + ' UTC');
        bids[i].innerHTML = date.toLocaleString();
        console.log(date.toString());
    }
}

convert_bids_localtime();
function convert_bids_localtime(){

    let bids = document.querySelectorAll('.bid-date');

    for (var i = 0; i < bids.length; i++) {
        let bid_date = bids[i];        
        // convert bid time to local time
        console.log('Date:');
        const date = new Date(new Date(bid_date.innerHTML).getTime() - new Date().getTimezoneOffset()*60*1000);
        bids[i].innerHTML = date.toLocaleString();
    }
}

function add_click_to_sorts(){
    const sorts_icon = document.querySelectorAll('.sort');
    for (var i = 0; i < sorts_icon.length; i++) {
        sorts_icon[i].addEventListener('click', async function() {

            
            const sort = this.getAttribute('sort');
            const order = this.getAttribute('order');
            const username = this.getAttribute('user');
            
            // update table
            // fech data from server url using ajax
            const response = await fetch("/api/user/"+username+"/bids?order=" + order + "&sort="+ sort);
            
            if(response.status != 200) return;

            const html_data = await response.text();

            // update bids section
            const bids_section = document.querySelector('#bids');
            bids_section.innerHTML = html_data;
            
            convert_bids_localtime();
            add_click_to_sorts();

            // update path
            const url = window.location.href;
            let new_url = '';
            if(url.includes('sort') && url.includes('order')){
                new_url = url.replace(/sort=(\w+)/, 'sort='+sort);
                new_url = new_url.replace(/order=(\w+)/, 'order='+order);
            }else{
                new_url = url.replace(location.hash,"");
                if(!new_url.includes('?'))
                    new_url = new_url + '?';
                new_url = new_url + '&sort='+sort+'&order='+order;
            }
            let stateObj = { id: "100" };
              
            window.history.pushState(stateObj, document.title, new_url);

            

        });
    }

}


convert_bids_localtime();
add_click_to_sorts();
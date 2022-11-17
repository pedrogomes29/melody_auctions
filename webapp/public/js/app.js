function addEventListeners() {
  let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
  [].forEach.call(itemCheckers, function(checker) {
    checker.addEventListener('change', sendItemUpdateRequest);
  });

  let itemCreators = document.querySelectorAll('article.card form.new_item');
  [].forEach.call(itemCreators, function(creator) {
    creator.addEventListener('submit', sendCreateItemRequest);
  });

  let itemDeleters = document.querySelectorAll('article.card li a.delete');
  [].forEach.call(itemDeleters, function(deleter) {
    deleter.addEventListener('click', sendDeleteItemRequest);
  });

  let cardDeleters = document.querySelectorAll('article.card header a.delete');
  [].forEach.call(cardDeleters, function(deleter) {
    deleter.addEventListener('click', sendDeleteCardRequest);
  });

  let cardCreator = document.querySelector('article.card form.new_card');
  if (cardCreator != null)
    cardCreator.addEventListener('submit', sendCreateCardRequest);


  // bids
  let load_bids = document.querySelector('#bidding_section #load_bids')
  if (load_bids != null)
    load_bids.addEventListener('click', load_more_bids);
}
addEventListeners();



async function load_more_bids(ev){
  
  let offset = ev.target.getAttribute('data-offset');
  let auction_id = ev.target.getAttribute('data-auction-id');
  let loading = document.querySelector('#bidding_section #loading');

  if(!auction_id)
    return;

  if(loading && loading.style.display == "none"){
    loading.style.display = "block";

    if (offset==null)
      offset=0;

    let request = await fetch("/api/auction/"+auction_id+"/bid?offset="+(offset));
    if(request.status == 200){
      console.log(request);

      let response = await request.text();
      let bidding_history = document.querySelector('#bidding_section #bidding_history');
      if(bidding_history && response!=''){
        bidding_history.innerHTML += response;
        ev.target.setAttribute('data-offset', offset+1);
      }

    }
    loading.style.display = "none";
  }

}


function loadImagePreview(imagecontainer){
  const container = document.querySelectorAll(imagecontainer)

  for(const c of container){
      const image = c.querySelector('img');
      const input = c.querySelector('input[type="file"]');

      if(input && image){

          const loadFile = function() {
              const reader = new FileReader();
              reader.onload = function(){
                  image.src = reader.result;
              };
              reader.readAsDataURL(input.files[0]);
          };

          input.addEventListener('change', loadFile);

      }

  }

}

loadImagePreview('.img-preview-container');

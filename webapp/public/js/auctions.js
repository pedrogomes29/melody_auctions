let offset = 0;

function start_countdowns() {
    let countdowns = document.querySelectorAll(".auction-card .countdown");

    [].forEach.call(countdowns, function (countdown) {
        const endDate = new Date(
            countdown.nextElementSibling.textContent
        ).getTime();
        const x = window.setInterval(function () {
            // Get today's date and time
            const localTime = new Date().getTime();
            const localOffset = new Date().getTimezoneOffset() * 60000;

            const now = localTime + localOffset;

            // Find the distance between now and the count down date
            const timeLeft = endDate - now;

            // Time calculations for days, hours, minutes and seconds
            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor(
                (timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
            );
            const minutes = Math.floor(
                (timeLeft % (1000 * 60 * 60)) / (1000 * 60)
            );
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            countdown.innerHTML =
                days +
                "d " +
                hours.toString().padStart(2, "0") +
                "h " +
                minutes.toString().padStart(2, "0") +
                "m " +
                seconds.toString().padStart(2, "0") +
                "s ";

            // If the count down is finished, write some text
            if (timeLeft < 0) {
                window.clearInterval(x);
                countdown.textContent = "";
            }
        }, 1000);
    });
}

start_countdowns();

function encodeForAjax(data) {
    if (data === null) return null;
    return Object.keys(data)
        .map(function (k) {
            return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]);
        })
        .join("&");
}

function addEventListeners() {
    const searchInput = document.getElementById("search_bar");
    const categories = document.querySelectorAll("#category .dropdown-item");
    [].forEach.call(categories, function (category) {
        category.addEventListener("click", chooseCategory);
    });

    const typeOptions = document.querySelectorAll("#type .dropdown-item");
    [].forEach.call(typeOptions, function (typeOption) {
        typeOption.addEventListener("click", chooseTypeOption);
    });

    addAuctionListeners();

    searchInput.addEventListener("input", async function () {
        if (
            document.querySelector("#auctionsOrUsers > #auctionsOrUsersButton")
                .textContent === "Auctions"
        ) {
            let search;
            if (
                document.querySelector(
                    "#auctionsOrUsers > #auctionsOrUsersButton"
                ).textContent === "Auctions"
            ) {
                search = searchInput.value ?? "";
            } else {
                search = "";
            }
            const url = new URL(window.location);
            url.searchParams.set("search", search);
            window.history.replaceState({}, "", url);
            offset = 0;
            getAuctions();
        }
    });


    const minPrice = document.getElementById("minPrice");

    const maxPrice = document.getElementById("maxPrice");
    
    async function filter_price() {
        // update url
        
        const inputValue = document.querySelectorAll('.numberVal span');
    
        let minrange = parseFloat( inputValue[0].innerHTML);
        let maxrange = parseFloat( inputValue[1].innerHTML);
        const url = new URL(window.location);
        
        url.searchParams.set("minPrice", minrange);
        url.searchParams.set("maxPrice", maxrange);
        window.history.replaceState({}, "", url);
        offset = 0;
    
        // update auctions
        getAuctions();
    }


    if (minPrice) {
        minPrice.addEventListener("change", filter_price);
    }

    if (maxPrice) {
        maxPrice.addEventListener("change", filter_price);
    }

    
    const auctionsOrder = document.getElementById("auctionsOrder");
    if(auctionsOrder) {
        auctionsOrder.addEventListener("change", async function () {
            // add parameter to url
            const url = new URL(window.location);
            url.searchParams.set("order", auctionsOrder.value);
            // reload page
            window.location.href = url;

        });
    }
}

async function chooseCategory(event) {
    previous_category = document.querySelector("#category .chosen");
    if (previous_category) previous_category.classList.remove("chosen");
    const selected = document.querySelector("#category > #categoryButton");
    selected.textContent = event.target.textContent;
    event.target.classList.add("chosen");
    offset = 0;

    const categoryElement = document.querySelector("#category .chosen");
    console.log(categoryElement);
    const url = new URL(window.location);
    if (categoryElement.id !== "")
        url.searchParams.set("categoryId", categoryElement.id.split("-")[1]);
    else url.searchParams.delete("categoryId");
    window.history.replaceState({}, "", url);

    getAuctions();
}

async function chooseTypeOption(event) {
    previous_category = document.querySelector("#type .chosen");
    if (previous_category) previous_category.classList.remove("chosen");
    const selected = document.querySelector("#type > #typeButton");
    selected.textContent = event.target.textContent;
    event.target.classList.add("chosen");
    offset = 0;

    const type = document.querySelector("#type #typeButton").textContent;

    if (type !== "Any type") {
        const url = new URL(window.location);
        url.searchParams.set("type", type.toLowerCase());
        window.history.replaceState({}, "", url);
    }else{
        const url = new URL(window.location);
        url.searchParams.delete("type");
        window.history.replaceState({}, "", url);
    }

    getAuctions();
}

async function getAuctions(offset = 0) {
    let params = {};
    const searchInput = document.getElementById("search_bar");
    if (
        document.querySelector("#auctionsOrUsers > #auctionsOrUsersButton")
            .textContent === "Auctions"
    )
        params.search = searchInput.value ?? "";

    const categoryElement = document.querySelector("#category .chosen");
    if (categoryElement.id !== '') params.categoryId = categoryElement.id.split("-")[1];

    const type = document.querySelector("#type #typeButton").textContent;
    if (type !== "None selected") params.type = type.toLowerCase();

    params.offset = offset;

    // get min and max price
    const minPrice = document.getElementById("minPrice");
    const minPriceValue = document.getElementById("minPriceValue").innerText;

    const maxPrice = document.getElementById("maxPrice");
    const maxPriceValue = document.getElementById("maxPriceValue").innerText;

    if (minPrice) params.minPrice = minPriceValue;
    if (maxPrice) params.maxPrice = maxPriceValue;

    const response = await fetch("/api/auction_html?" + encodeForAjax(params));
    const newAuctions = await response.text();
    console.log(newAuctions);
    if (offset > 0) {
        // delete auction footer
        const current_auctions_footer = document.getElementById("auctions-footer");
        current_auctions_footer.parentElement.removeChild(current_auctions_footer);

        // select current auctions
        const current_auctions = document.querySelector("#auctions_lists :nth-child(1)");
        
        // append new auctions
        let aux = document.createElement("div");
        aux.innerHTML = newAuctions;
        const appended_auctions = aux.children[0];
        const new_footer = aux.children[1];
        current_auctions.innerHTML += appended_auctions.innerHTML;
        document.getElementById("auctions_lists").appendChild(new_footer);
    } else {
        let nrAuctionsHTML = document.getElementById("nrAuctions");

        let aux = document.createElement("div");
        aux.innerHTML = newAuctions; //element representing the fetched html
        const nrAuctions = aux.children[1].lastElementChild.textContent.split(" ")[3]; //gets number of auctions from the footer of the fetched HTML(Showing x of y results)
        nrAuctionsHTML.textContent = nrAuctions + " results";
        let auctions = document.getElementById("auctions_lists");
        auctions.innerHTML = newAuctions;

    }

    addAuctionListeners();

    start_countdowns();
    convert_localtime();
}

function chooseAuction(event) {
    window.location.href = "/auction/" + event.currentTarget.id.split("-")[1];
}

function addAuctionListeners() {
    const auctions = document.getElementsByClassName("auction-card");
    [].forEach.call(auctions, function (auction) {
        auction.addEventListener("click", chooseAuction);
    });
    const loadMore = document.getElementById("load-more");
    if (loadMore) loadMore.addEventListener("click", load_more);
}

async function load_more() {
    offset++;
    getAuctions(offset);
}

addEventListeners();




const range= document.querySelectorAll('.range-slider input');
progress = document.querySelector('.range-slider .progress');
const gap = 0.1;
const inputValue = document.querySelectorAll('.numberVal span');

range.forEach( input => {
    input.addEventListener('input', ajustProgress)
})

ajustProgress()

function ajustProgress(e){
    let minrange = parseFloat(range[0].value);
    let maxrange = parseFloat(range[1].value);

    if(maxrange - minrange < gap){
        if(e.target.className === "range-min"){
            range[0].value = maxrange - gap;
        }
        else{
            range[1].value = minrange + gap;
        } 
    } 
    else{
        progress.style.left= (minrange/ range[0].max) * 100 + '%';
        progress.style.right= 100 - (maxrange/ range[1].max) * 100 + '%';
        inputValue[0].innerHTML = minrange;
        inputValue[1].innerHTML = maxrange;
    }
}



function convert_localtime(){

    let dates = document.querySelectorAll('.local-date');

    for (var i = 0; i < dates.length; i++) {
        let date_element = dates[i];        
        if(date_element.getAttribute('data-converted') == 'true') continue;
        // convert bid time to local time
        const date = new Date(new Date(date_element.innerHTML).getTime() - new Date().getTimezoneOffset()*60*1000);
        dates[i].innerHTML = date.toLocaleString();
        dates[i].setAttribute('data-converted', 'true');
    }
}

convert_localtime();
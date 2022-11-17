let offset = 0;

function start_countdowns() {
    let countdowns = document.querySelectorAll(".card .countdown");

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

    const ongoingOptions = document.querySelectorAll("#ongoing .dropdown-item");
    [].forEach.call(ongoingOptions, function (ongoingOption) {
        ongoingOption.addEventListener("click", chooseOngoingOption);
    });

    addAuctionListeners();

    searchInput.addEventListener("input", async function () {
        if (
            document.querySelector("#auctionsOrUsers > #dropdownMenuButton1")
                .textContent === "Auctions"
        ) {
            let search;
            if (
                document.querySelector(
                    "#auctionsOrUsers > #dropdownMenuButton1"
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

    const loadMore = document.getElementById("load-more");
    if (loadMore) loadMore.addEventListener("click", load_more);
}

async function chooseCategory(event) {
    previous_category = document.querySelector("#category .chosen");
    if (previous_category) previous_category.classList.remove("chosen");
    const selected = document.querySelector("#category > #dropdownMenuButton2");
    selected.textContent = event.target.textContent;
    event.target.classList.add("chosen");
    offset = 0;

    const categoryElement = document.querySelector("#category .chosen");
    let categoryId;
    if (categoryElement) categoryId = categoryElement.id.split("-")[1];
    else categoryId = -1;

    const url = new URL(window.location);
    url.searchParams.set("categoryId", categoryId);
    window.history.replaceState({}, "", url);

    getAuctions();
}

async function chooseOngoingOption(event) {
    previous_category = document.querySelector("#ongoing .chosen");
    if (previous_category) previous_category.classList.remove("chosen");
    const selected = document.querySelector("#ongoing > #dropdownMenuButton3");
    selected.textContent = event.target.textContent;
    event.target.classList.add("chosen");
    offset = 0;

    const ongoingString = document.querySelector(
        "#ongoing #dropdownMenuButton3"
    ).textContent;
    const ongoing =
        ongoingString === "None selected"
            ? -1
            : ongoingString === "Active"
            ? 1
            : 0;
    const url = new URL(window.location);
    url.searchParams.set("ongoing", ongoing);
    window.history.replaceState({}, "", url);

    getAuctions();
}

async function getAuctions(offset = 0) {
    const searchInput = document.getElementById("search_bar");
    let search;
    if (
        document.querySelector("#auctionsOrUsers > #dropdownMenuButton1")
            .textContent === "Auctions"
    ) {
        search = searchInput.value ?? "";
    } else {
        search = "";
    }
    const categoryElement = document.querySelector("#category .chosen");
    let categoryId;
    if (categoryElement) categoryId = categoryElement.id.split("-")[1];
    else categoryId = -1;

    const ongoingString = document.querySelector(
        "#ongoing #dropdownMenuButton3"
    ).textContent;

    const ongoing =
        ongoingString === "None selected"
            ? -1
            : ongoingString === "Active"
            ? 1
            : 0;

    const response = await fetch(
        "/api/auction_html?" +
            encodeForAjax({
                search: search,
                categoryId: categoryId,
                ongoing: ongoing,
                offset: offset,
            })
    );
    const newAuctions = await response.text();
    if (offset > 0) {
        const current_auctions_footer =
            document.getElementById("auctions-footer");
        current_auctions_footer.parentElement.removeChild(
            current_auctions_footer
        );

        const current_auctions = document.querySelector(
            "#auctions :first-child"
        );

        let aux = document.createElement("div");
        aux.innerHTML = newAuctions;

        const appended_auctions = aux.children[0];
        const new_footer = aux.children[1];

        current_auctions.innerHTML += appended_auctions.innerHTML;
        document.getElementById("auctions").appendChild(new_footer);
    } else document.getElementById("auctions").innerHTML = newAuctions;

    addAuctionListeners();

    start_countdowns();
}

function chooseAuction(event) {
    window.location.href = "/auction/" + event.currentTarget.id.split("-")[1];
}

function addAuctionListeners() {
    const auctions = document.getElementsByClassName("card");
    [].forEach.call(auctions, function (auction) {
        auction.addEventListener("click", chooseAuction);
    });
}

async function load_more() {
    offset++;
    getAuctions(offset);
}

addEventListeners();

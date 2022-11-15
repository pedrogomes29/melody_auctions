function encodeForAjax(data) {
    if (data === null) return null;
    return Object.keys(data)
        .map(function (k) {
            return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]);
        })
        .join("&");
}

function addEventListeners() {
    let searchInput = document.getElementById("search_bar");
    searchInput.addEventListener("input", async function () {
        if (
            document.querySelector("#auctionsOrUsers > #dropdownMenuButton1")
                .textContent === "Auctions"
        ) {
            const response = await fetch(
                "/api/auction_json?search=" + (searchInput.value ?? "")
            );
            const auctions = await response.json();
            showAuctions(auctions);
        }
    });

    let searchButton = document.getElementById("search-button");
    searchButton.addEventListener("click", search);

    searchInput.addEventListener("keyup", function (event) {
        event.preventDefault();
        if (event.key === "Enter") searchButton.click();
    });
}

function search() {
    let searchInput = document.getElementById("search_bar");
    if (
        document.querySelector("#auctionsOrUsers > #dropdownMenuButton1")
            .textContent === "Auctions"
    ) {
        location.replace(
            "/auction?" + encodeForAjax({ search: searchInput.value })
        );
    }
}

function showAuctions(auctions) {
    console.log(auctions);
    const dropdown_menu = document.querySelector(
        "#search_results .dropdown-menu"
    );
    dropdown_menu.innerHTML = "";
    for (const auction of auctions) {
        const auctionHTML = document.createElement("div");
        auctionHTML.classList.add("dropdown-item");
        const auctionName = document.createElement("h4");
        const auctionDescription = document.createElement("p");
        auctionName.textContent = auction.name;
        auctionDescription.textContent = auction.description;
        auctionHTML.appendChild(auctionName);
        auctionHTML.appendChild(auctionDescription);
        auctionHTML.addEventListener("click", function () {
            location.replace("/auction/" + auction.id);
        });
        dropdown_menu.appendChild(auctionHTML);
    }
    dropdown_menu.classList.add("show");
}

addEventListeners();

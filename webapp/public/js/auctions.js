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

    searchInput.addEventListener("input", async function () {
        if (
            document.querySelector("#auctionsOrUsers > #dropdownMenuButton1")
                .textContent === "Auctions"
        )
            getAuctions();
    });
}

async function chooseCategory(event) {
    previous_category = document.querySelector("#category .chosen");
    if (previous_category) previous_category.classList.remove("chosen");
    const selected = document.querySelector("#category > #dropdownMenuButton2");
    selected.textContent = event.target.textContent;
    event.target.classList.add("chosen");
    getAuctions();
}

async function chooseOngoingOption(event) {
    previous_category = document.querySelector("#ongoing .chosen");
    if (previous_category) previous_category.classList.remove("chosen");
    const selected = document.querySelector("#ongoing > #dropdownMenuButton3");
    selected.textContent = event.target.textContent;
    event.target.classList.add("chosen");
    getAuctions();
}

async function getAuctions() {
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
            })
    );
    const html_response = await response.text();
    const [nrAuctions, ...rest] = html_response.split("-");

    const newAuctions = rest.join("-");
    document.getElementById("auctions").innerHTML = newAuctions;
    document.getElementById("auctions-count").textContent = nrAuctions;
}

addEventListeners();

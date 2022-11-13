function addEventListeners() {
    let select = document.getElementById("select");
    select.addEventListener("click", toggleDropDown);
    let options = document.getElementsByClassName("options");
    [].forEach.call(options, function (option) {
        option.addEventListener("click", chooseOption);
    });

    let searchInput = document.getElementById("search_bar");
    searchInput.addEventListener("input", async function () {
        if (document.getElementById("selected").textContent == "Auctions") {
            const response = await fetch(
                "/api/auction?search=" + (searchInput.value ?? "")
            );
            const auctions = await response.json();
            showAuctions(auctions);
        } else {
            const response = await fetch(
                "/api/user?search=" + (searchInput.value ?? "")
            );
            const users = await response.json();
            showUsers(users);
        }
    });
}

function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data)
        .map(function (k) {
            return encodeURIComponent(k) + "=" + encodeURIComponent(data[k]);
        })
        .join("&");
}

function toggleDropDown() {
    let dropdown = document.getElementById("hidden_dropdown");
    dropdown.classList.toggle("hide");
}

function chooseOption(event) {
    let selected = document.getElementById("selected");
    selected.innerHTML = event.target.innerHTML;
}

function showAuctions(auctions) {
    console.log(auctions);
}

function showUsers(users) {
    console.log(users);
}

addEventListeners();

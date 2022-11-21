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

function addEventListeners() {
    const categories = document.querySelectorAll("#categories .category");
    [].forEach.call(categories, function (category) {
        category.addEventListener("click", chooseCategory);
    });

    const onGoingButton = document.getElementById("ongoing-button");
    if (onGoingButton) onGoingButton.addEventListener("click", clickOnGoing);

    const uninitiatedButton = document.getElementById("uninitiated-button");
    if (uninitiatedButton)
        uninitiatedButton.addEventListener("click", clickUninitiated);

    const closedButton = document.getElementById("closed-button");
    if (closedButton) closedButton.addEventListener("click", clickClosed);

    const auctions = document.getElementsByClassName("card");
    [].forEach.call(auctions, function (auction) {
        auction.addEventListener("click", chooseAuction);
    });
}

function chooseCategory(event) {
    const searchInput = document.getElementById("search_bar");
    window.location.href =
        "/auction?" +
        (search == ""
            ? encodeForAjax({
                  search: searchInput.value,
                  categoryId: event.target.id.split("-")[1],
              })
            : encodeForAjax({
                  categoryId: event.target.id.split("-")[1],
              }));
}

function clickUninitiated() {
    const searchInput = document.getElementById("search_bar");
    window.location.href =
        "/auction?" +
        (search == ""
            ? encodeForAjax({
                  search: searchInput.value,
                  type: "uninitiated",
              })
            : encodeForAjax({
                  type: "uninitiated",
              }));
}

function clickOnGoing() {
    const searchInput = document.getElementById("search_bar");
    window.location.href =
        "/auction?" +
        (search == ""
            ? encodeForAjax({
                  search: searchInput.value,
                  type: "active",
              })
            : encodeForAjax({
                  type: "active",
              }));
}

function clickClosed() {
    const searchInput = document.getElementById("search_bar");
    window.location.href =
        "/auction?" +
        (search == ""
            ? encodeForAjax({
                  search: searchInput.value,
                  type: "closed",
              })
            : encodeForAjax({
                  type: "closed",
              }));
}

function chooseAuction(event) {
    window.location.href = "/auction/" + event.currentTarget.id.split("-")[1];
}

addEventListeners();

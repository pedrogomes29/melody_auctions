function timeSince(date) {
    const localTime = new Date().getTime();
    const localOffset = new Date().getTimezoneOffset() * 60000;

    const now = localTime + localOffset;

    const seconds = (now - date) / 1000;

    let interval = seconds / 31536000;

    if (interval > 1) {
        return `${Math.floor(interval)} year${interval >= 2 ? "s" : ""} ago`;
    }
    interval = seconds / 2592000;
    if (interval > 1) {
        return `${Math.floor(interval)} month${interval >= 2 ? "s" : ""} ago`;
    }
    interval = seconds / 86400;
    if (interval > 1) {
        return `${Math.floor(interval)} day${interval >= 2 ? "s" : ""} ago`;
    }
    interval = seconds / 3600;
    if (interval > 1) {
        return `${Math.floor(interval)} hour${interval >= 2 ? "s" : ""} ago`;
    }
    interval = seconds / 60;
    if (interval > 1) {
        return `${Math.floor(interval)} minute${interval >= 2 ? "s" : ""} ago`;
    }
    if (seconds > 10) return `${Math.floor(seconds)} seconds ago`;
    else return "just now";
}

function addEventListeners() {
    const options = document.querySelectorAll(
        "#auctionsOrUsers .dropdown-item"
    );
    [].forEach.call(options, function (option) {
        option.addEventListener("click", chooseOption);
    });

    const searchInput = document.getElementById("search_bar");
    searchInput.addEventListener("input", async function () {
        if (
            document.querySelector("#auctionsOrUsers > #auctionsOrUsersButton")
                .textContent === "Users"
        ) {
            const response = await fetch(
                "/api/user?search=" + (searchInput.value ?? "")
            );
            const users = await response.json();
            showUsers(users);
        }
    });

    const userProfilePic = document.getElementById("user-profile");
    if (userProfilePic)
        userProfilePic.addEventListener("click", seeUserProfile);

    const adminProfilePic = document.getElementById("admin-profile");
    if (adminProfilePic)
        adminProfilePic.addEventListener("click", seeAdminProfile);

    document
        .getElementById("logo")
        .addEventListener("click", () => (window.location.href = "/"));

    // bids
    let load_bids = document.querySelector("#bidding_section #load_bids");
    if (load_bids != null) load_bids.addEventListener("click", load_more_bids);

    const notificationBell = document.getElementById("notificationBell");
    if (notificationBell)
        notificationBell.addEventListener("click", clickNotificationBell);

    const auctionNotifications = document.querySelectorAll(".sec");
    [].forEach.call(auctionNotifications, function (auctionNotification) {
        auctionNotification.addEventListener("click", chooseNotification);
    });
}

function clickNotificationBell() {
    if (
        document
            .getElementById("notifications")
            .classList.toggle("showNotifications")
    ) {
        markNotificationsAsRead();
        updateNotificationTimeSince();
    } else markNotificationsAsReadDOM();
}

function seeUserProfile(event) {
    window.location.href = "/user/" + event.currentTarget.classList[0];
}

function seeAdminProfile(event) {
    window.location.href = "/admin/" + event.currentTarget.classList[0];
}

function chooseOption(event) {
    previous_option = document.querySelector("#auctionsOrUsers .chosen");

    previous_option.classList.remove("chosen");
    const selected = document.querySelector(
        "#auctionsOrUsers > #auctionsOrUsersButton"
    );
    selected.textContent = event.target.textContent;
    event.target.classList.add("chosen");
}

window.onclick = function (event) {
    if (!event.target.matches(".dropdown-item")) {
        const dropdown_menu = document.querySelector(
            "#search_results .dropdown-menu"
        );
        if (dropdown_menu.classList.contains("show"))
            dropdown_menu.classList.remove("show");
    }

    if (!event.target.matches("#notificationsContainer *")) {
        markNotificationsAsReadDOM();
    }
};

window.onscroll = function () {
    const dropdown_menu = document.querySelector(
        "#search_results .dropdown-menu"
    );
    if (dropdown_menu.classList.contains("show"))
        dropdown_menu.classList.remove("show");

    markNotificationsAsReadDOM();
};

async function markNotificationsAsReadDOM() {
    const nofications = document.getElementById("notifications");
    if (nofications.classList.contains("showNotifications"))
        nofications.classList.remove("showNotifications");
    window.setTimeout(function () {
        const auctionNotifications = document.querySelectorAll(".sec");
        [].forEach.call(auctionNotifications, function (auctionNotification) {
            if (auctionNotification.classList.contains("new"))
                auctionNotification.classList.remove("new");
        });
    }, 500);
}

function showUsers(users) {
    console.log(users);
    const dropdown_menu = document.querySelector(
        "#search_results .dropdown-menu"
    );

    dropdown_menu.innerHTML = "";

    if (users.length == 0) {
        const html = document.createElement("div");
        const noResults = document.createElement("h4");
        noResults.classList.add("ml-3");
        html.appendChild(noResults);
        noResults.innerText = "No results";
        dropdown_menu.appendChild(html);
    }

    for (const user of users) {
        const userHTML = document.createElement("div");
        userHTML.classList.add("dropdown-item");
        userHTML.classList.add("ml-3");
        const userName = document.createElement("h4");
        const userDescription = document.createElement("p");
        userName.textContent = user.username;
        userDescription.textContent = user.description;
        userHTML.appendChild(userName);
        userHTML.appendChild(userDescription);
        dropdown_menu.appendChild(userHTML);
        userHTML.addEventListener("click", function () {
            window.location.href = "/user/" + user.username;
        });
    }
    if (!dropdown_menu.classList.contains("show"))
        dropdown_menu.classList.add("show");
}
addEventListeners();

// Bids

async function load_more_bids(ev) {
    let offset = ev.target.getAttribute("data-offset");
    let auction_id = ev.target.getAttribute("data-auction-id");
    let loading = document.querySelector("#bidding_section #loading");

    if (!auction_id) return;

    if (loading && loading.style.display == "none") {
        loading.style.display = "block";

        if (offset == null) offset = 0;

        let request = await fetch(
            "/api/auction/" + auction_id + "/bid?offset=" + offset
        );
        if (request.status == 200) {
            console.log(request);

            let response = await request.text();
            let bidding_history = document.querySelector(
                "#bidding_section #bidding_history"
            );
            if (bidding_history && response != "") {
                bidding_history.innerHTML += response;
                ev.target.setAttribute("data-offset", offset + 1);
            }
        }
        loading.style.display = "none";
    }
}

function loadImagePreview(imagecontainer) {
    const container = document.querySelectorAll(imagecontainer);

    for (const c of container) {
        const image = c.querySelector("img");
        const input = c.querySelector('input[type="file"]');

        if (input && image) {
            const loadFile = function () {
                const reader = new FileReader();
                reader.onload = function () {
                    image.src = reader.result;
                };
                reader.readAsDataURL(input.files[0]);
            };

            input.addEventListener("change", loadFile);
        }
    }
}

loadImagePreview(".img-preview-container");

function form_create_auction(form) {
    var startdate = new Date(form.querySelector("input[name=startDate]").value);
    var userTimezoneOffset = startdate.getTimezoneOffset() * 60000;
    form.querySelector("input[name=startDate]").value = new Date(
        startdate.getTime() - userTimezoneOffset
    )
        .toISOString()
        .slice(0, 16);

    var enddate = new Date(form.querySelector("input[name=endDate]").value);
    var userTimezoneOffset = enddate.getTimezoneOffset() * 60000;
    form.querySelector("input[name=endDate]").value = new Date(
        enddate.getTime() - userTimezoneOffset
    )
        .toISOString()
        .slice(0, 16);

    return true;
}

async function markNotificationsAsRead() {
    document.getElementById("numberOfNotifications").innerText = "";
    const response = await fetch(
        window.location.protocol +
            "//" +
            window.location.host +
            `/api/notifications/${window.User.id}`,
        {
            method: "put",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        }
    );
    console.log(await response.json());
}

function updateNotificationTimeSince() {
    const everyNotificationTimeSince = document.querySelectorAll(
        "#notifications .notificationContent.sub"
    );
    [].forEach.call(
        everyNotificationTimeSince,
        function (notificationTimeSince) {
            const notificationDate = new Date(
                notificationTimeSince.nextElementSibling.textContent
            ).getTime();
            notificationTimeSince.innerText = timeSince(notificationDate);
        }
    );
}

function chooseNotification(event) {
    window.location.href = "/auction/" + event.currentTarget.id;
}

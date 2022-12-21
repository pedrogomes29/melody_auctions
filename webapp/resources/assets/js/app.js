/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require("./bootstrap");

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
    if (seconds > 10) return `${Math.floor(interval)} seconds ago`;
    else return "just now";
}

function insertNotification(type, auction, notificationDateResponse, bidder) {
    const notification = document.createElement("div");
    notification.id = auction.id;
    notification.classList.add("sec");
    notification.classList.add("new");

    const notificationPhoto = document.createElement("div");
    notificationPhoto.classList.add("notificationPhoto");

    const auctionPhoto = document.createElement("img");
    auctionPhoto.classList.add("auctionPhoto");
    auctionPhoto.src =
        window.location.protocol + "//" + window.location.host + "/";
    if (auction.photo != "") auctionPhoto.src += auction.photo;
    else auctionPhoto.src += "default_images/auction_default.svg";

    notificationPhoto.appendChild(auctionPhoto);

    const notificationMessage = document.createElement("div");
    notificationMessage.classList.add("notificationContent");
    switch (type) {
        case "AuctionCancelled":
            notificationMessage.innerText = `${auction.name} was cancelled`;
            break;

        case "AuctionEnded":
            notificationMessage.innerText = `${auction.name} ended`;
            break;

        case "AuctionEnding":
            notificationMessage.innerText = `${auction.name} is ending in less than 30 minutes`;
            break;

        case "Bid":
            notificationMessage.innerText = `${bidder} has bid on ${auction.name}`;
            break;
    }

    const timeSinceElem = document.createElement("div");
    timeSinceElem.classList.add("notificationContent");
    timeSinceElem.classList.add("sub");
    const aux = new Date(notificationDateResponse).getTime();
    timeSinceElem.textContent = timeSince(aux);

    const notificationDate = document.createElement("div");
    notificationDate.classList.add("notificationDate");
    notificationDate.hidden = true;
    notificationDate.innerText = notificationDateResponse;

    notification.appendChild(notificationPhoto);
    notification.appendChild(notificationMessage);
    notification.appendChild(timeSinceElem);
    notification.appendChild(notificationDate);

    const container = document.querySelector(
        "#notifications > .display > .cont"
    );

    if (container.classList.contains("nothing")) {
        container.classList.remove("nothing");
        container.innerHTML = "";
    }
    container.insertBefore(notification, container.firstChild);
}

function incrementNotificationCount() {
    const numberOfNotifications = document.getElementById(
        "numberOfNotifications"
    );
    const countHTML = numberOfNotifications.innerText;
    const count = countHTML ? parseInt(countHTML) : 0;
    numberOfNotifications.innerText = count + 1;

    notificationContainer = document.getElementById("notificationsContainer");
    notificationContainer.classList.remove("notify");
    notificationContainer.classList.add("notify");
}

Echo.private(`users.${window.User.id}`).listen("AuctionCancelled", (e) => {
    incrementNotificationCount();
    insertNotification("AuctionCancelled", e.auction, e.notification_date);
});

Echo.private(`users.${window.User.id}`).listen("NewBid", (e) => {
    incrementNotificationCount();
    insertNotification("Bid", e.auction, e.notification_date, e.bidder);
});

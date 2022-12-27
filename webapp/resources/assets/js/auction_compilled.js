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

function addMessageToDOM(
    user,
    message,
    messageDateResponse,
    messageByOtherPerson
) {
    const messageContainer = document.createElement("div");
    messageContainer.classList.add("d-flex");
    messageContainer.classList.add("flex-row");
    if (messageByOtherPerson)
        messageContainer.classList.add("justify-content-start");
    else messageContainer.classList.add("justify-content-end");
    messageContainer.classList.add("mb-4");

    const messageContent = document.createElement("div");

    const messageText = document.createElement("div");
    messageText.classList.add("small");
    messageText.classList.add("p-2");
    messageText.classList.add("mb-1");
    messageText.classList.add("rounded-3");
    if (messageByOtherPerson) {
        messageText.classList.add("ms-3");
        messageText.classList.add("another-person-text");
    } else {
        messageText.classList.add("me-3");
        messageText.classList.add("text-white");
        messageText.classList.add("bg-primary");
    }

    messageText.textContent = message;

    const messageTimeSince = document.createElement("div");
    messageTimeSince.classList.add("small");
    messageTimeSince.classList.add("mb-1");
    messageTimeSince.classList.add("rounded-3");
    messageTimeSince.classList.add("text-muted");
    if (messageByOtherPerson) messageTimeSince.classList.add("ms-3");
    else {
        messageTimeSince.classList.add("me-3");
        messageTimeSince.classList.add("d-flex");
        messageTimeSince.classList.add("justify-content-end");
    }
    const aux = new Date(messageDateResponse).getTime();
    messageTimeSince.textContent = `${user.username}, ${timeSince(aux)}`;

    const messageDate = document.createElement("div");
    messageDate.classList.add("messageDate");
    messageDate.hidden = true;
    messageDate.innerText = messageDateResponse;

    messageContent.appendChild(messageText);
    messageContent.appendChild(messageTimeSince);
    messageContent.appendChild(messageDate);

    const userPhoto = document.createElement("img");
    userPhoto.classList.add(user.username);
    userPhoto.classList.add("rounded-circle");
    userPhoto.classList.add("msg-pfp");
    userPhoto.alt = "Sender Profile Picutre";
    userPhoto.src =
        window.location.protocol + "//" + window.location.host + "/";
    if (user.photo != "") userPhoto.src += user.photo;
    else userPhoto.src += "default_images/default.jpg";

    userPhoto.addEventListener("click", function (e) {
        window.location.href = "/user/" + e.currentTarget.classList[0];
    });

    if (messageByOtherPerson) {
        messageContainer.appendChild(userPhoto);
        messageContainer.appendChild(messageContent);
    } else {
        messageContainer.appendChild(messageContent);
        messageContainer.appendChild(userPhoto);
    }

    chatbox = document.getElementById("chatboxContent");
    chatbox.appendChild(messageContainer);
}

function scrollChat() {
    const chatbox = document.getElementById("chatboxContent");
    chatbox.scrollTop = chatbox.scrollHeight;
}

Echo.channel(`auction.${window.location.pathname.split("/").pop()}`).listen(
    "NewMessage",
    (e) => {
        addMessageToDOM(
            e.user,
            e.message,
            e.messageDate,
            window.User ? e.user.id != window.User.id : true
        );
        scrollChat();
        updateMessagesTimeSince();
    }
);

function updateMessagesTimeSince() {
    const everyMessageDate = document.querySelectorAll(
        "#chatboxContent .messageDate"
    );
    [].forEach.call(everyMessageDate, function (messageDateHTML) {
        const messageDate = new Date(messageDateHTML.textContent).getTime();
        messageDateHTML.previousElementSibling.innerText =
            timeSince(messageDate);
    });
}

userPhotos = document.getElementsByClassName("msg-pfp");
[].forEach.call(userPhotos, function (userPhoto) {
    userPhoto.addEventListener("click", function (e) {
        window.location.href = "/user/" + e.currentTarget.classList[0];
    });
});
window.addEventListener("load", scrollChat);

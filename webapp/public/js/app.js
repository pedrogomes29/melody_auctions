function addEventListeners() {
    let options = document.querySelectorAll("#auctionsOrUsers .dropdown-item");
    [].forEach.call(options, function (option) {
        option.addEventListener("click", chooseOption);
    });

    let searchInput = document.getElementById("search_bar");
    searchInput.addEventListener("input", async function () {
        if (
            document.querySelector("#auctionsOrUsers > #dropdownMenuButton1")
                .textContent === "Users"
        ) {
            const response = await fetch(
                "/api/user?search=" + (searchInput.value ?? "")
            );
            const users = await response.json();
            showUsers(users);
        }
    });
}

function chooseOption(event) {
    previous_option = document.querySelector("#auctionsOrUsers .chosen");

    previous_option.classList.remove("chosen");
    let selected = document.querySelector(
        "#auctionsOrUsers > #dropdownMenuButton1"
    );
    selected.textContent = event.target.textContent;
    event.target.classList.add("chosen");
}

window.onclick = function (event) {
    if (!event.target.matches(".dropdown-item")) {
        let dropdown_menu = document.querySelector(
            "#search_results .dropdown-menu"
        );
        if (dropdown_menu.classList.contains("show"))
            dropdown_menu.classList.remove("show");
    }
};

window.onscroll = function () {
    let dropdown_menu = document.querySelector(
        "#search_results .dropdown-menu"
    );
    if (dropdown_menu.classList.contains("show"))
        dropdown_menu.classList.remove("show");
};

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

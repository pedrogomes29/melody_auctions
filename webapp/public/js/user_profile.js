const field = document.querySelector('[name="username"]');

field.addEventListener("keypress", function (event) {
    const key = event.code;
    if (key === "Space") {
        event.preventDefault();
    }
});

const pic = document.getElementById("prof_pic");
pic.addEventListener("click", () => {
    const form = document.getElementById("image_form");
    if (form.style.display === "none") {
        // 👇️ this SHOWS the form
        form.style.display = "block";
    } else {
        // 👇️ this HIDES the form
        form.style.display = "none";
    }
    const imageUpload = document.getElementById("imageUpload");
    imageUpload.click();
});

function previewImage(input) {
    const file = document.querySelector("input[type=file]").files[0];
    const reader = new FileReader();

    reader.addEventListener(
        "load",
        function () {
            // convert image file to base64 string
            document.getElementById("real_pic").src = reader.result;
            document.getElementById("default_pic").src = reader.result;
        },
        false
    );

    if (file) {
        reader.readAsDataURL(file);
    }
}

async function postData(data, url, token) {
    return fetch(url, {
        method: "put",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-CSRF-TOKEN": token,
        },
        body: encodeForAjax(data),
    });
}

const form = document.getElementById("add-balance-form");

form.addEventListener("submit", async (e) => {
    e.preventDefault();
    url = form.getAttribute("action");
    token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const data = {
        balance: document.getElementById("balance-input").value,
    };
    postData(data, url, token)
        .catch(() => console.error("Network Error"))
        .then((response) => response.json())
        .catch(() => console.error("Error parsing JSON"))
        .then((json) => console.log(json));
    balance = document.getElementById("actual_balance");
    if (balance) {
        console.log(balance);
        balance.innerHTML =
            parseInt(balance.innerHTML) + parseInt(data.balance);
    }
});

const auctions_owned_button = document.getElementById("owned-button");
auctions_owned_button.addEventListener("click", (e) => {
    window.location.href += "/auction";
});

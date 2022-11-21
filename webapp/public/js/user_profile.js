const field = document.querySelector('[name="username"]');

field.addEventListener("keypress", function (event) {
    const key = event.code;
    if (key === "Space") {
        event.preventDefault();
    }
});

const pic = document.getElementById("prof_pic");
pic.addEventListener("click", () => {
    const imageUpload = document.getElementById("imageUpload");
    imageUpload.click();
});

const imageUpload = document.getElementById("imageUpload");
imageUpload.addEventListener("change", previewImage);

function previewImage() {
    const file = document.querySelector("input[type=file]").files[0];
    console.log(file);
    const reader = new FileReader();

    reader.addEventListener(
        "load",
        function () {
            // convert image file to base64 string
            const form = document.getElementById("image_form");
            if (document.getElementById("real_pic"))
                document.getElementById("real_pic").src = reader.result;
            if (document.getElementById("default_pic"))
                document.getElementById("default_pic").src = reader.result;
            form.style.display = "block";
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
if (auctions_owned_button)
    auctions_owned_button.addEventListener("click", (e) => {
        window.location.href += "/auction";
    });

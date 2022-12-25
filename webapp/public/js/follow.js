let is_following = document.getElementById("followed-bool").textContent;
if (is_following == 1) {
    const form = document.getElementById("unfollow-form")
    form.style.display = "block";
} else {
    const form = document.getElementById("follow-form")
    form.style.display = "block";
}

// Ajax for follow/unfollow
async function postData(data, url, token) {
    return fetch(url, {
        method: "post",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-CSRF-TOKEN": token,
        },
        body: encodeForAjax(data),
    });
}
async function deleteData(data, url, token) {
    return fetch(url, {
        method: "delete",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-CSRF-TOKEN": token,
        },
        body: encodeForAjax(data),
    });
}

const follow_form = document.getElementById("follow-form");
const unfollow_form = document.getElementById("unfollow-form");

follow_form.addEventListener("submit", async (e) => {
    e.preventDefault();
    url = follow_form.getAttribute("action");
    token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const data = {
        auction_id: document.getElementById("auction-id").textContent,
    };
    postData(data, url, token)
    .catch(() => console.error("Network Error"))
    .then((response) => response.json())
    .catch(() => console.error("Error parsing JSON"))
    .then((json) => console.log(json));
    follow_form.style.display = "none";
    unfollow_form.style.display = "block";
});

unfollow_form.addEventListener("submit", async (e) => {
    e.preventDefault();
    url = unfollow_form.getAttribute("action");
    token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const data = {
        auction_id: document.getElementById("auction-id").textContent,
    };
    deleteData(data, url, token)
    .catch(() => console.error("Network Error"))
    .then((response) => response.json())
    .catch(() => console.error("Error parsing JSON"))
    .then((json) => console.log(json));
    unfollow_form.style.display = "none";
    follow_form.style.display = "block";
});



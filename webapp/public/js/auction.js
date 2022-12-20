function start_countDown(countdown) {
    const endDate = new Date(countdown.getAttribute("date-date")).getTime();
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
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
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
            location.reload();
        }
    }, 1000);
}

function add_countDown() {
    const countd = document.querySelector("#bid_auction #auction_countdown");
    if (countd) start_countDown(countd);
}

add_countDown();


async function  deleteAuction(obj) {

    const response = await fetch(obj.getAttribute("data-auction"), {
        method: 'delete',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-CSRF-TOKEN': obj.getAttribute("data-csrf")
        }
      })

    if(response.status == 400){
        const errors = await response.json();
        const popuperror = document.querySelector("#popupError");
        popuperror.innerHTML = errors.error;
        popuperror.style.display = "block";
        document.querySelector(".modal-body").scrollTop=0

    }
    else if(response.status == 200){
        location.reload();
    }else{
        location.reload();
    }

}


async function  updateAuction(obj) {

    const formElement = document.querySelector("#update");

    const data = new URLSearchParams();
    for (const pair of new FormData(formElement)) {
        data.append(pair[0], pair[1]);
    }

    const response = await fetch(formElement.action, {
        method: 'put',
        body: data,
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': formElement.querySelector("[name=_token]").value
    }
    })

    if(response.status == 400){
        const errors = (await response.json()).error;
        const popuperror = document.querySelector("#popupError");
        if(errors.length > 0){
            popuperror.innerHTML = errors[0];
            popuperror.style.display = "block";
            document.querySelector(".modal-body").scrollTop=0
        }
    }
    else if(response.status == 200){
        location.reload();
    }else{
        location.reload();
    }

}


async function  updatePhoto(event) {
    event.preventDefault();
    const formElement = document.querySelector("#store");
    const input = formElement.querySelector("input[type=file]");
    
    let data = new FormData()
    data.append('photo', input.files[0])
    const response = await fetch(formElement.action, {
        method: 'POST',
        body: data,
        headers: {
        'X-CSRF-TOKEN': formElement.querySelector("[name=_token]").value
        }
    })

    console.log(response);

    if(response.status == 400){
        const errors = (await response.json());
        const popuperror = document.querySelector("#popupError");
        popuperror.innerHTML = errors.error;
        popuperror.style.display = "block";
        document.querySelector(".modal-body").scrollTop=0
        
    }
    else if(response.status == 200){
        location.reload();
    }

}

async function setDefaultImage(obj) {

    const response = await fetch(obj.getAttribute("data-auction"), {
        method: 'post',
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': obj.getAttribute("data-csrf")
        }
    })
    console.log(response);
    if(response.status == 400){
        const errors = (await response.json());
        const popuperror = document.querySelector("#popupError");
        popuperror.innerHTML = errors.error;
        popuperror.style.display = "block";
        document.querySelector(".modal-body").scrollTop=0
        
    }
    else if(response.status == 200){
        location.reload();
    } 
}

async function adminUpdateAuction(obj) {
    const formElement = document.querySelector("#adminUpdate");

    const data = new URLSearchParams();
    for (const pair of new FormData(formElement)) {
        data.append(pair[0], pair[1]);
    }

    const response = await fetch(formElement.action, {
        method: 'put',
        body: data,
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': formElement.querySelector("[name=_token]").value
        }
    })
    if(response.status == 400){
        const errors = (await response.json()).error;
        const popuperror = document.querySelector("#popupError");
        if(errors.length > 0){
            popuperror.innerHTML = errors[0];
            popuperror.style.display = "block";
            document.querySelector(".modal-body").scrollTop=0
        }
    }
    else if(response.status == 200){
        location.reload();
    }
}

async function adminDeleteAuction(obj) {
    
        const response = await fetch(obj.getAttribute("data-auction"), {
            method: 'delete',
            headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': obj.getAttribute("data-csrf")
            }
        })
    
        if(response.status == 400){
            const errors = await response.json();
            const popuperror = document.querySelector("#popupError");
            popuperror.innerHTML = errors.error;
            popuperror.style.display = "block";
            document.querySelector(".modal-body").scrollTop=0
    
        }
        else if(response.status == 200){
            location.reload();
        }else{
            location.reload();
        }
    
}

function addEventListeners(){
    const updateImageForm = document.querySelector("#store");

    if(updateImageForm){
        updateImageForm.addEventListener("submit", updatePhoto);
    }
}

addEventListeners();

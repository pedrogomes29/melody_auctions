function addEventListeners () {
    const states = document.getElementsByClassName("state");
    [].forEach.call(states, function (state) {
        state.addEventListener("click", chooseState);
    });
}

async function chooseState(event) {
    this.blur();
    previous_state = document.querySelector(".chosen");
    const reports = document.getElementsByClassName("report");
    if (previous_state == event.target){
        previous_state.classList.remove("chosen"); 
        for (let i = 0; i < reports.length; i++) {
            if (reports[i].classList.contains("hidden")) {
                reports[i].classList.remove("hidden");
            }
        }
        return;
    } else {
        if (previous_state) previous_state.classList.remove("chosen");
        event.target.classList.add("chosen");
    }
    const state = event.target.textContent.toLowerCase();
    for (let i = 0; i < reports.length; i++) {
        if (reports[i].classList.contains(state)) {
            reports[i].classList.remove("hidden");
        } else {
            reports[i].classList.add("hidden");
        }
    }
  
}


addEventListeners();
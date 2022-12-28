const reviewButton = document.getElementById("review-button");

reviewButton.addEventListener("click", (e) => {
    const form = document.getElementById("review-form");
    if (form.style.display === 'none') {
        // 👇️ this SHOWS the form
        form.style.display = 'block';
      } else {
        // 👇️ this HIDES the form
        form.style.display = 'none';
      }
});
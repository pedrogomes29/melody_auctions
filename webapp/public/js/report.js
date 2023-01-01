const reportButton = document.getElementById('report-button');

reportButton.addEventListener('click', (e) => {
    const form = document.getElementById('report-form');
    if (form.style.display == 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
});
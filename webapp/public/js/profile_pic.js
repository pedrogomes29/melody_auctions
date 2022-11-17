const pic = document.getElementById('prof_pic');
pic.addEventListener('click', () => {
  const form = document.getElementById('image_form');
  if (form.style.display === 'none') {
    // 👇️ this SHOWS the form
    form.style.display = 'block';
  } else {
    // 👇️ this HIDES the form
    form.style.display = 'none';
  }
});
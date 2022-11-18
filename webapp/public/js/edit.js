const btn = document.getElementById('editprofile');

btn.addEventListener('click', () => {
  const form = document.getElementById('edituser');

  if (form.style.display === 'none') {
    // 👇️ this SHOWS the form
    form.style.display = 'block';
  } else {
    // 👇️ this HIDES the form
    form.style.display = 'none';
  }
});

const btn2 = document.getElementById('add-balance');
btn2.addEventListener('click', () => {
  const form = document.getElementById('add-balance-form');

  if (form.style.display === 'none') {
    // 👇️ this SHOWS the form
    form.style.display = 'block';
  } else {
    // 👇️ this HIDES the form
    form.style.display = 'none';
  }
});
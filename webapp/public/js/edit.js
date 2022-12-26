
const bt_balance = document.getElementById('add-balance');
bt_balance.addEventListener('click', () => {
  const form = document.getElementById('add-balance-form');

  if (form.style.display === 'none') {
    // 👇️ this SHOWS the form
    form.style.display = 'block';
  } else {
    // 👇️ this HIDES the form
    form.style.display = 'none';
  }
});
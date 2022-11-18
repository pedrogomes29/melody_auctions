
async function postData(data, url, token) {
    return fetch(url, {
      method: 'put',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': token
      },
      body: encodeForAjax(data)
    })
  }

const form = document.getElementById('add-balance-form')

form.addEventListener('submit', async (e) => {
    e.preventDefault()
    url =  form.getAttribute('action')
    token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    const data = {
      balance: document.getElementById('balance-input').value,
    }
    postData(data, url, token)
        .catch(() => console.error('Network Error'))
        .then(response => response.json())
        .catch(() => console.error('Error parsing JSON'))
        .then(json => console.log(json))
    balance = document.getElementById('actual_balance')
    if (balance) {
        console.log(balance)
        balance.innerHTML = parseInt(balance.innerHTML) + parseInt(data.balance)
    }
  });
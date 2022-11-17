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
  const imageUpload = document.getElementById('imageUpload');
  imageUpload.click();
});

function previewImage(input) {
  const file = document.querySelector('input[type=file]').files[0];
  const reader = new FileReader();

  reader.addEventListener(
    'load',
    function () {
      // convert image file to base64 string
      document.getElementById('real_pic').src = reader.result;
      document.getElementById('default_pic').src = reader.result;
    },
    false
  );

  if (file) {
    reader.readAsDataURL(file);
  }
}
// Previsualização da imagem de perfil
const imgInput = document.getElementById('imgUpload');
const imgLabel = document.getElementById('imgLabel');

imgInput.addEventListener('change', function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      imgLabel.style.backgroundImage = `url('${e.target.result}')`;
      imgLabel.textContent = '';
    }
    reader.readAsDataURL(file);
  }
});


  const telefoneInput = document.getElementById('telefone');
  telefoneInput.addEventListener('input', function (e) {
    let value = e.target.value.replace(/\D/g, '');

    if (value.length > 11) value = value.slice(0, 11);

    if (value.length > 6) {
      value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
    } else if (value.length > 2) {
      value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
    } else if (value.length > 0) {
      value = `(${value}`;
    }

    e.target.value = value;
  });

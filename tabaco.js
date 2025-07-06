const passwordIcons = document.querySelectorAll('.password-icon');

passwordIcons.forEach(icon => {
    icon.addEventListener('click', function () {
        const input = this.parentElement.querySelector('.form-control');
        input.type = input.type === 'password' ? 'text' : 'password';
        this.classList.toggle('fa-eye');
    })
})


$(document).ready(function() {
    let count = 1;

    $('#add-button').click(function() {
        let nomeBotao = prompt("Digite o nome do novo botão:");
        if (nomeBotao) {
            let newButton = `<label class="btn btn-secondary">
                                <input type="radio" name="options" autocomplete="off"> ${nomeBotao}
                             </label>`;
                             <input type="hidden" name="nome" value="nomeArea"></input>
            $(newButton).insertBefore('#add-button');
        }
    });
});

function editField(icon) {
    let input = icon.nextElementSibling;
    input.removeAttribute('readonly');
    input.focus();
    input.addEventListener('blur', function() {
        input.setAttribute('readonly', true);
    }, { once: true });
}
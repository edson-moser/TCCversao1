function recuperarSenha() {
    var email = document.getElementById("email").value.trim();
    if (!email) {
        alert("Digite seu e-mail.");
        return;
    }
    
    if (!/\S+@\S+\.\S+/.test(email)) {
        alert("E-mail inválido.");
        return;
    }

    var formData = new FormData();
    formData.append("email", email);

    fetch("RecuperarSenhaBackend.php", { // troque para o nome correto do seu PHP
        method: "POST",
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if (res.sucesso) {
            window.location.href = "recovery-password-message.html";
        } else {
            alert(res.mensagem || "E-mail não encontrado.");
        }
    })
    .catch(() => alert("Erro ao enviar. Tente novamente."));
}
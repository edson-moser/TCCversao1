<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <script src="RecuperarSenha.js"></script>
    <link rel="stylesheet" href="RecuperarSenha.css">
</head>
<body>
    <div class="centraliza-tela">
        <div class="recuperar-container">
            <h2>Para recuperar a sua senha,<br>insira o e-mail cadastrado</h2>
            <form id="form-recovery-password" onsubmit="return false" method="post" class="recuperar-form">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="email@email.com" required />
                <button type="submit" onclick="recuperarSenha()">Recuperar senha</button>
            </form>
            <a href="login.html" class="cancelar-link">Cancelar</a>
        </div>
    </div>
</body>
</html>
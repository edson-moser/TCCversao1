<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src = "RecuperarSenha.js"></script>
    <link rel="stylesheet" href="RecuperarSenha.css">
</head>
<body>
    <div class = "grid-vertical">
        <div class = "container-content">
            <h1 class = "title">Para recuperar a sua senha, <br /> insira o e-mail cadastrado</h1>
            <form id = "form-recovery-password" onsubmit = "return false" method = "post" class = "form-centered">
                <div class = "form-group">
                    <label for = "email">E-mail</label>
                    <input type = "email" id = "email" name = "email" placeholder = "email@email.com" />

                </div>
            </form>
        </div>
        <div class= "group-buttons">
            <a class="button button-primary" onclick="recuperarSenha()">Recuperar senha</a>
            <a class = "button button-secondary" href="login.html">Cancelar</a>
        </div>
    </div>
</body>
</html>
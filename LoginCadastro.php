
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Naturis</title>
    <link rel="stylesheet" href="LoginCadastro.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
        integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
        <link rel="icon" sizes="32X32" href="NaturisLogo.png">
</head>
<body>

   <div class="imagem-fundo">
    <div class="container">
        <div class="content first-content">
            <div class="first-column">
             
                <h2 class="title title-primary">Bem-vindo produtor!</h2>
                <p class="description description-primary">Se você já possui cadastro,</p>
                <p class="description description-primary">Por favor faça login com Email e senha.</p>
                <button id="signin" class="btn btn-primary">Entrar</button>
            </div>    
            <div class="second-column">
                <h2 class="title title-second">Cadastre-se</h2>
               
                <p class="description description-second">Informe seus dados e crie uma senha:</p>
<form method="post" action="produtor_Cadastro.php">
    <label class="label-input">
        <i class="far fa-user icon-modify"></i>
        <input type="text" name="nome" placeholder="Nome" required>
    </label>
    
    <label class="label-input">
        <i class="far fa-envelope icon-modify"></i>
        <input type="email" name="email" placeholder="Email" required>
    </label>
    
    <label class="label-input">
        <i class="fas fa-lock icon-modify"></i>
        <input type="password" name="senha" placeholder="Senha" required>
    </label>
    
    <label class="label-input">
        <i class="fas fa-lock icon-modify"></i>
        <input type="password" name="confirma_senha" placeholder="Confirmar senha" required>
    </label>
    
    <button type="submit" class="btn btn-second">Cadastrar</button>        
</form>
            </div><!-- second column -->
        </div><!-- first content -->
        <div class="content second-content">
            <div class="first-column">
                <h2 class="title title-primary">Olá produtor!</h2>
                <p class="description description-primary">Caso ainda não esteje cadastrado insira seus dados
                    </p>
                <p class="description description-primary">e vamos transformar sua propriedade.</p>
                <button id="signup" class="btn btn-primary">Cadastrar</button>
            </div>
            <div class="second-column">
                <h2 class="title title-second">Faça o login</h2>
                
                <p class="description description-second">Insira o Email e sua senha:</p>
               
                <form class="form" method="POST" action="login.php">
                    <label class="label-input" for="">
                        <i class="far fa-envelope icon-modify"></i>
                        <input name="email" type="email" placeholder="email">
                    </label>
                
                    <label class="label-input" for="">
                        <i class="fas fa-lock icon-modify"></i>
                        <input name="senha" type="password" placeholder="Senha">
                    </label>
                
                    <a class="password" href="#">Esqueceu a senha?</a>
                    <button type="submit" name="login">Entrar</button>
                        <a class="nav-link" href="paginaInicial.php">Inicio</a>
                
                </form>
            </div><!-- second column -->
        </div><!-- second-content -->
    </div>
</div>
    <script src="LoginCadastro.js"></script>
</body>
</html>
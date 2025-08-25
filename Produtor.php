<?php



include('protect.php');
include('conexao.php');
$produtor_id = $_SESSION['idprodutor'] ?? null;

    $pdp = $conecta->query("SELECT*FROM produtor WHERE idprodutor=$produtor_id")->fetch_assoc(); ?>

    

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Produtor- Naturis</title>
    <link rel="icon" sizes="32X32" href="NaturisLogo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Produtor.css" />
    <script src="Produtor.js" defer></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#"><img class="imagemBarra" src="NaturisLogo.png"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="paginaInicial.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tabaco.php">Tabaco</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="eucalipto.php">Eucalipto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clima.php">Clima</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Historico.php">Históricos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produtor.php">Produtor</a>
                    </li>
                    
                </ul>
            </div>
        </nav>
    </header>

    <div class="container">
    <h2 class="titulo-eucalipto">Perfil do Agricultor</h2>
<form method="post" action="cadastrar_produtor.php">
   <!--DESMARCAR DEPOIS DA FEIRA E EXCLUIR a imagem
 <div class="profile-img">
        <input type="file" id="imgUpload" accept="image/*" />
         <label for="imgUpload" id="imgLabel">Adicionar Imagem</label> 
    </div>-->
<div id="perfil-container">
    <img src="imgFeira.png" alt="Foto de perfil" class="perfil">
</div>
        <div class="form-group">
            <label for="nome">Nome completo</label>
            <input type="text" value="<?=$pdp['nome'] ?>" id="nome" name="nome" placeholder="Seu nome completo" disabled />
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" value="<?=$pdp['email'] ?>" id="email" name="email" placeholder="Seu e-mail" disabled />
        </div>

        <div class="form-group">
            <label for="cidade">Cidade</label>
            <input type="text" value="<?=$pdp['cidade'] ?>" id="cidade" name="cidade" placeholder="Sua cidade" disabled />
        </div>

        <?php
$estados = [
    "AC" => "Acre (AC)",
    "AL" => "Alagoas (AL)",
    "AP" => "Amapá (AP)",
    "AM" => "Amazonas (AM)",
    "BA" => "Bahia (BA)",
    "CE" => "Ceará (CE)",
    "DF" => "Distrito Federal (DF)",
    "ES" => "Espírito Santo (ES)",
    "GO" => "Goiás (GO)",
    "MA" => "Maranhão (MA)",
    "MT" => "Mato Grosso (MT)",
    "MS" => "Mato Grosso do Sul (MS)",
    "MG" => "Minas Gerais (MG)",
    "PA" => "Pará (PA)",
    "PB" => "Paraíba (PB)",
    "PR" => "Paraná (PR)",
    "PE" => "Pernambuco (PE)",
    "PI" => "Piauí (PI)",
    "RJ" => "Rio de Janeiro (RJ)",
    "RN" => "Rio Grande do Norte (RN)",
    "RS" => "Rio Grande do Sul (RS)",
    "RO" => "Rondônia (RO)",
    "RR" => "Roraima (RR)",
    "SC" => "Santa Catarina (SC)",
    "SP" => "São Paulo (SP)",
    "SE" => "Sergipe (SE)",
    "TO" => "Tocantins (TO)"
];
?>

<div class="form-group">
    <label for="estado">Estado</label>
    <select id="estado" name="estado" disabled>
        <option>Selecione seu estado</option>
        <?php foreach ($estados as $sigla => $nome): ?>
            <option value="<?= $sigla ?>" <?= $pdp['estado'] == $sigla ? 'selected' : '' ?>>
                <?= $nome ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="tel" value="<?=$pdp['telefone'] ?>" id="telefone" name="telefone" placeholder="(00) 00000-0000" maxlength="15" minlength="15" disabled>
        </div>

        <div class="form-group">
            <label for="data_nascimento">Data de nascimento</label>
            <input type="date" value="<?=$pdp['dataNascimento'] ?>" name="dataNascimento" id="data_nascimento" required disabled>
        </div>

        <div class="buttons">
            <button type="button" id="btnAlterar" class="btn-action">ALTERAR DADOS</button>
            <button type="button" id="btnSalvar" class="btn-action" style="display: none;">SALVAR DADOS</button>

            <button type="button" class="link-button" onclick="location.href='logout.php'">Sair do site</button>
            <button type="button" class="link-button">Alterar Senha</button>
        </div>
    </form>
</div>

<!-- Script para alternar entre editar e salvar -->
<script>
    document.getElementById("btnAlterar").addEventListener("click", function () {
        // Habilita todos os campos do formulário
        document.querySelectorAll("form input, form select").forEach(el => {
            el.disabled = false;
        });

        // Alterna os botões
        document.getElementById("btnAlterar").style.display = "none";
        document.getElementById("btnSalvar").style.display = "inline-block";
    });

    document.getElementById("btnSalvar").addEventListener("click", function () {
        // Garante que tudo está habilitado no momento do envio
        document.querySelectorAll("form input, form select").forEach(el => {
            el.disabled = false;
        });

        // Envia o formulário manualmente
        document.querySelector("form").submit();
    });
</script>

</body>

</html>
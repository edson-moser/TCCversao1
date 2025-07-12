
<?php

include('cadastrarTabaco.php');
include('protect.php');
include('conexao.php');

$produtor_id = $_SESSION['idprodutor'] ?? null;
$periodoSelecionado = $_GET['periodoSafra'] ?? null; // Passado via GET ao selecionar a safra

if ($produtor_id && $periodoSelecionado) {
    // 1. Buscar a safra de tabaco do produtor
    $stmtTabaco = $conecta->prepare("SELECT * FROM tabaco WHERE produtor_idprodutor = ? AND periodoSafra = ?");
    $stmtTabaco->bind_param("is", $produtor_id, $periodoSelecionado);
    $stmtTabaco->execute();
    $resultTabaco = $stmtTabaco->get_result();

    if ($resultTabaco->num_rows > 0) {
        $tabaco = $resultTabaco->fetch_assoc();

//         // 2. Buscar as áreas relacionadas a esse tabaco
//         $stmtAreas = $conecta->prepare("SELECT * FROM area WHERE tabaco_idtabaco = ?");
//         $stmtAreas->bind_param("i", $tabaco['idtabaco']);
//         $stmtAreas->execute();
//         $resultAreas = $stmtAreas->get_result();

//         $areas = [];
//         while ($row = $resultAreas->fetch_assoc()) {
//             $areas[] = $row;
//         }

//     } else {
//         echo "<p>Nenhuma safra encontrada para o período selecionado.</p>";
    }
}
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="tabaco.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
    <script src="tabaco.js"></script>
    <link rel="icon" sizes="32X32" href="NaturisLogo.png">
    <title>Tabaco - Naturis</title>
</head>

<body>
    <!--Barra de opções-->
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

    <main id="form_container">
        <h2 class="titulo-Tabaco">ADICIONAR INFORMAÇÕES DA PRODUÇÃO DE TABACO:</h2>

        <!--caracteristicas gerais-->
        <form action="cadastrarTabaco.php" method="post" id="form">

            <div id="input_container">

                <div class="input-box">
    <label for="periodoSafra" class="form-label">Período</label>
    <div class="input-field">
        <select id="periodoSafra" name="periodoSafra" class="form-control" onchange="carregarSafra()">
            <option value="">Selecione o período</option>
        </select>
    </div>
</div>

<script>
    const select = document.getElementById("periodoSafra");
    const selectedPeriodo = "<?= $periodoSelecionado ?? '' ?>";

    for (let ano = 1950; ano < 2080; ano++) {
        const valor = `${ano}-${ano + 1}`;
        const option = document.createElement("option");
        option.value = valor;
        option.textContent = valor;

        if (valor === selectedPeriodo) {
            option.selected = true;
        }

        select.appendChild(option);
    }
</script>

                </div>

                <div class="input-box">
                    <label for="total_plantado" class="form-label">TOTAL DE PÉS PLANTADOS</label>
                    <div class="input-field">
                        <input type="number" value="<?=$tabaco['total'] ?>" name="total" id="total_plantado" class="form-control"
                            placeholder="Mil pés">

                    </div>
                </div>
                  <div class="input-box">
                    <label for="quilos_produzidos" class="form-label">VALOR TOTAL DA VENDA</label>
                    <div class="input-field">
                        <input type="number" value="<?=$tabaco['precoTotal'] ?>" name="precoTotal" id="precoTotal" class="form-control"
                            placeholder="Preços das vendas">
                    </div>
                </div>

                <div class="input-box">
                    <label for="quilos_produzidos" class="form-label">QUILOS PRODUZIDOS</label>
                    <div class="input-field">
                        <input type="number" value="<?=$tabaco['kilos'] ?>" name="kilos" id="quilos_produzidos" class="form-control"
                            placeholder="Quilos">
                    </div>
                </div>

                <div class="input-box">
                    <label for="quantidade_estufadas" class="form-label">TOTAL DE ESTUFADAS</label>
                    <div class="input-field">
                        <input type="number" value="<?=$tabaco['estufadas'] ?>"  name="estufadas" id="quantidade_estufadas" class="form-control"
                            placeholder="1, 2, 3, 4, 5...">

                    </div>
                </div>

                <div class="input-box">
                    <label for="total_hectares" class="form-label">TOTAL DE HECTARES</label>
                    <div class="input-field">
                        <input type="number" value="<?=$tabaco['totalHectares'] ?>" name="totalHectares" id="total_hectares" class="form-control"
                            placeholder="Hectares">

                    </div>
                </div>
            </div>
            <button type="submit" class="btn-default">
                <i class="fa-solid fa-check"></i>
                SALVAR DADOS DA SAFRA
            </button>
            </form>
        
        <form action="cadastrarArea.php" method="post" id="form">

<?php
require 'conexao.php';
include 'protect.php';
include 'cadastrarTabaco.php';

// Buscar lista de áreas
$areas = $conecta->query("SELECT idarea, nome FROM area")->fetch_all(MYSQLI_ASSOC);

// Se `idarea` veio via GET, buscar dados
$dados = null;
if (isset($_GET['idarea'])) {
    $idarea = $_GET['idarea'];
    $stmt = $conecta->prepare("SELECT * FROM area WHERE idarea = ?");
    $stmt->bind_param("i", $idarea);
    $stmt->execute();
    $dados = $stmt->get_result()->fetch_assoc();
}

// Mensagem de resultado
$mensagem = $_GET['mensagem'] ?? '';
?>

<form method="POST" action="cadastrarArea.php">
    <label>Selecionar Área para Editar:</label>
    <select name="idareaSelecionada" onchange="location = '?idarea=' + this.value">
        <option value="">Selecione a área</option>\
        <?php foreach ($areas as $a): ?>
            <option value="<?= $a['idarea'] ?>" <?= ($dados && $dados['idarea'] == $a['idarea']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($a['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="hidden" name="idarea" value="<?= $dados['idarea'] ?? '' ?>">
    <input type="text" name="nome" placeholder="Nome da Área" value="<?= $dados['nome'] ?? '' ?>">
    <input type="number" name="qtdPes" placeholder="Qtd Pés" value="<?= $dados['qtdPes'] ?? '' ?>">
    <input type="number" name="hectares" placeholder="Hectares" value="<?= $dados['hectares'] ?? '' ?>">
    <input type="date" name="dataInicio" value="<?= $dados['dataInicio'] ?? '' ?>">
    <input type="date" name="dataFim" value="<?= $dados['dataFim'] ?? '' ?>">
    <input type="text" name="variedades" placeholder="Variedades" value="<?= $dados['variedades'] ?? '' ?>">
    <input type="text" name="produtos" placeholder="Produtos" value="<?= $dados['produtos'] ?? '' ?>">
    <input type="text" name="pragasDoencas" placeholder="Pragas" value="<?= $dados['pragasDoencas'] ?? '' ?>">
    <input type="text" name="agrotoxicos" placeholder="Agrotóxicos" value="<?= $dados['agrotoxicos'] ?? '' ?>">
    <input type="number" name="mediaFolhas" placeholder="Média de Folhas" value="<?= $dados['mediaFolhas'] ?? '' ?>">
    <input type="number" name="colheitas" placeholder="Colheitas" value="<?= $dados['colheitas'] ?? '' ?>">
    <input type="text" name="periodoEscondido" value="<?= $_GET['periodo'] ?? '' ?>" hidden>

    <button type="submit" name="salvar">Salvar</button>
    <?php if ($dados): ?>
        <button type="submit" name="excluir" onclick="return confirm('Excluir esta área?')">Excluir</button>
    <?php endif; ?>
</form>

<p><?= htmlspecialchars($mensagem) ?></p>



    </main>
    <script>
function carregarSafra() {
    let periodo = document.getElementById("periodoSafra").value;
    if (periodo !== "") {
        window.location.href = "?periodoSafra=" + encodeURIComponent(periodo);
    }
}
</script>
    <script>
    $(document).ready(function () {
        let count = 1;

        $('#add-button').click(function () {
            let nomeBotao = prompt("Digite o nome do novo botão:");
            if (nomeBotao) {
                let newButton = `<label class="btn btn-secondary">
                                    <input type="radio" name="options" value="${nomeBotao}" checked autocomplete="off"> ${nomeBotao}
                                </label>
                                <input type="hidden" name="nome" value="${nomeBotao}">`;

                $(newButton).insertBefore('#add-button');
                $('#nome_area').val(nomeBotao); // atualiza o campo hidden com o nome do botão
            }
        });
    });
</script>

    <script>
        const sel = document.getElementById('periodo');
        const hid = document.getElementById('periodoEscondido');
        sel.addEventListener('change', () => hid.value = sel.value);
    </script>

</body>

</html>
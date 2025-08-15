<?php
include('protect.php');
include('conexao.php');
include('cadastrarTabaco.php');

$produtor_id = $_SESSION['idprodutor'] ?? null;
$periodoSelecionado = $_GET['periodoSafra'] ?? null;

$tabaco = [];
$areasSafra = [];
$idtabaco = null;

// Busca o tabaco a Inserir
if ($produtor_id && $periodoSelecionado) {
    $stmtTabaco = $conecta->prepare("SELECT * FROM tabaco WHERE produtor_idprodutor = ? AND periodoSafra = ?");
    $stmtTabaco->bind_param("is", $produtor_id, $periodoSelecionado);
    $stmtTabaco->execute();
    $resultTabaco = $stmtTabaco->get_result();

    if ($resultTabaco->num_rows === 0) {
        $stmtInsert = $conecta->prepare("INSERT INTO tabaco (produtor_idprodutor, periodoSafra) VALUES (?, ?)");
        $stmtInsert->bind_param("is", $produtor_id, $periodoSelecionado);
        $stmtInsert->execute();
        $idtabaco = $stmtInsert->insert_id;
    } else {
        $tabaco = $resultTabaco->fetch_assoc();
        $idtabaco = $tabaco['idtabaco'];
    }

    // Buscar áreas dessa safra
    $stmtAreasSafra = $conecta->prepare("SELECT * FROM area WHERE tabaco_idtabaco = ?");
    $stmtAreasSafra->bind_param("i", $idtabaco);
    $stmtAreasSafra->execute();
    $resultAreasSafra = $stmtAreasSafra->get_result();
    while ($row = $resultAreasSafra->fetch_assoc()) {
        $areasSafra[] = $row;
    }
}

$areasEdicao = $conecta->query("SELECT idarea, nome FROM area")->fetch_all(MYSQLI_ASSOC);

$dadosArea = null;
if (isset($_GET['idarea'])) {
    $idarea = $_GET['idarea'];
    $stmt = $conecta->prepare("SELECT * FROM area WHERE idarea = ?");
    $stmt->bind_param("i", $idarea);
    $stmt->execute();
    $dadosArea = $stmt->get_result()->fetch_assoc();
}

$mensagem = $_GET['mensagem'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="tabaco.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="tabaco.js"></script>
    <link rel="icon" sizes="32x32" href="NaturisLogo.png">
    <title>Tabaco - Naturis</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><img class="imagemBarra" src="NaturisLogo.png"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="paginaInicial.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="tabaco.php">Tabaco</a></li>
            <li class="nav-item"><a class="nav-link" href="eucalipto.php">Eucalipto</a></li>
            <li class="nav-item"><a class="nav-link" href="clima.php">Clima</a></li>
            <li class="nav-item"><a class="nav-link" href="Historico.php">Históricos</a></li>
            <li class="nav-item"><a class="nav-link" href="produtor.php">Produtor</a></li>
        </ul>
    </div>
</nav>

<main id="form_container">
    <h2 class="titulo-Tabaco">ADICIONAR INFORMAÇÕES DA PRODUÇÃO DE TABACO:</h2>

    <!-- Form Safra -->
    <form action="cadastrarTabaco.php" method="post">
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
                    if (valor === selectedPeriodo) option.selected = true;
                    select.appendChild(option);
                }
            </script>

            <div class="input-box">
                <label class="form-label">TOTAL DE PÉS PLANTADOS</label>
                <input type="number" value="<?= $tabaco['total'] ?? '' ?>" name="total" class="form-control" placeholder="total de pés">
            </div>
            <div class="input-box">
                <label class="form-label">VALOR TOTAL DA VENDA</label>
                <input type="number" value="<?= $tabaco['precoTotal'] ?? '' ?>" name="precoTotal" class="form-control" placeholder="R$">
            </div>
            <div class="input-box">
                <label class="form-label">QUILOS PRODUZIDOS</label>
                <input type="number" value="<?= $tabaco['kilos'] ?? '' ?>" name="kilos" class="form-control"placeholder="kg">
            </div>
            <div class="input-box">
                <label class="form-label">TOTAL DE ESTUFADAS</label>
                <input type="number" value="<?= $tabaco['estufadas'] ?? '' ?>" name="estufadas" class="form-control"placeholder="1,2,3,4,5.....">
            </div>
            <div class="input-box">
                <label class="form-label">TOTAL DE HECTARES</label>
                <input type="number" value="<?= $tabaco['totalHectares'] ?? '' ?>" name="totalHectares" class="form-control"placeholder="1,2,3,4,5.....">
            </div>
        </div>
        <button type="submit" class="btn-default"><i class="fa-solid fa-check"></i> SALVAR DADOS DA SAFRA</button>
    </form>

    <!-- Form Área -->
    <form method="POST" action="cadastrarArea.php">
        <input type="hidden" name="periodoEscondido" value="<?= $periodoSelecionado ?>">

        <div id="input_container">
            <div class="input-box" id="campo_area">
                <label class="form-label">Adicionar área ou click e selecione uma área:</label>
                <select name="idareaSelecionada" onchange="location = '?periodoSafra=<?= urlencode($periodoSelecionado) ?>&idarea=' + this.value" class="form-control">
                    <option value="">Nova área:</option>
                    <?php foreach ($areasEdicao as $a): ?>
                        <option value="<?= $a['idarea'] ?>" <?= ($dadosArea && $dadosArea['idarea'] == $a['idarea']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" name="idarea" value="<?= $dadosArea['idarea'] ?? '' ?>">

            <?php
            $campos = [
                ['nome', 'Nome da Área', 'text'],
                ['qtdPes', 'Qtd Pés', 'number'],
                ['hectares', 'Hectares', 'number'],
                ['dataInicio', 'Data de Início', 'date'],
                ['dataFim', 'Data de Fim', 'date'],
                ['variedades', 'Variedades', 'text'],
                ['produtos', 'Produtos', 'text'],
                ['pragasDoencas', 'Pragas', 'text'],
                ['agrotoxicos', 'Agrotóxicos', 'text'],
                ['mediaFolhas', 'Média de Folhas', 'number'],
                ['colheitas', 'Colheitas', 'number'],
            ];
            foreach ($campos as [$nome, $label, $tipo]): ?>
                <div class="input-box">
                    <label for="<?= $nome ?>" class="form-label"><?= $label ?></label>
                    <input type="<?= $tipo ?>" name="<?= $nome ?>" id="<?= $nome ?>" value="<?= $dadosArea[$nome] ?? '' ?>" class="form-control" placeholder="<?= $label ?>">
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" name="salvar" class="btn-default"><i class="fa-solid fa-check"></i> SALVAR ÁREA</button>
        <?php if ($dadosArea): ?>
            <button type="submit" name="excluir" class="btn-default" style="background-color:#8b0000;" onclick="return confirm('Excluir esta área?')">
                <i class="fa-solid fa-trash"></i> EXCLUIR ÁREA
            </button>
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
</body>
</html>

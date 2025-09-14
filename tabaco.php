<?php
include('protect.php');
include('conexao.php');
include('cadastrarTabaco.php');

$produtor_id = $_SESSION['idprodutor'] ?? null;
$periodoSelecionado = $_GET['periodoSafra'] ?? null;

$tabaco = [];
$areasSafra = [];
$idtabaco = null;

// Buscar tabaco da safra
if ($produtor_id && $periodoSelecionado) {
    $stmtTabaco = $conecta->prepare("SELECT * FROM tabaco WHERE produtor_idprodutor = ? AND periodoSafra = ?");
    $stmtTabaco->bind_param("is", $produtor_id, $periodoSelecionado);
    $stmtTabaco->execute();
    $resultTabaco = $stmtTabaco->get_result();

    if ($resultTabaco && $resultTabaco->num_rows > 0) {
        // existe: pega o primeiro registro
        $tabaco = $resultTabaco->fetch_assoc();
        if ($tabaco && isset($tabaco['idtabaco'])) {
            $idtabaco = (int) $tabaco['idtabaco'];
        }
    } else {
        // não existe: insere e obtém id
        $stmtInsert = $conecta->prepare("INSERT INTO tabaco (produtor_idprodutor, periodoSafra) VALUES (?, ?)");
        $stmtInsert->bind_param("is", $produtor_id, $periodoSelecionado);
        $stmtInsert->execute();
        $idtabaco = $stmtInsert->insert_id ? (int) $stmtInsert->insert_id : null;
        // opcional: recarregar $tabaco se quiser usar os campos imediatamente
        if ($idtabaco) {
            $stmtReload = $conecta->prepare("SELECT * FROM tabaco WHERE idtabaco = ?");
            $stmtReload->bind_param("i", $idtabaco);
            $stmtReload->execute();
            $tabaco = $stmtReload->get_result()->fetch_assoc();
        }
    }
}


// Buscar áreas da safra
if ($idtabaco) {
    $stmtAreasSafra = $conecta->prepare("SELECT * FROM area WHERE tabaco_idtabaco = ?");
    $stmtAreasSafra->bind_param("i", $idtabaco);
    $stmtAreasSafra->execute();
    $resultAreasSafra = $stmtAreasSafra->get_result();
    while ($row = $resultAreasSafra->fetch_assoc()) {
        $areasSafra[] = $row;
    }
}

$areasEdicao = $areasSafra; 

$dadosArea = null;
$idareaSelecionada = $_POST['idarea'] ?? $_GET['idarea'] ?? null;

if ($idareaSelecionada && $idtabaco) {
    $stmt = $conecta->prepare("SELECT * FROM area WHERE idarea = ? AND tabaco_idtabaco = ?");
    $stmt->bind_param("ii", $idareaSelecionada, $idtabaco);
    $stmt->execute();
    $dadosArea = $stmt->get_result()->fetch_assoc();
}

$mensagem = $_GET['mensagem'] ?? '';

$totalPes = 0;
$totalHectares = 0;
$totalEstufadas = 0;

if ($idtabaco) {
    $stmtSoma = $conecta->prepare("
        SELECT 
            SUM(qtdPes) AS totalPes, 
            SUM(hectares) AS totalHectares 
        FROM area 
        WHERE tabaco_idtabaco = ?
    ");
    $stmtSoma->bind_param("i", $idtabaco);
    $stmtSoma->execute();
    $resultSoma = $stmtSoma->get_result();
    if ($row = $resultSoma->fetch_assoc()) {
        $totalPes = $row['totalPes'] ?? 0;
        $totalHectares = $row['totalHectares'] ?? 0;
        $totalEstufadas = $row['totalEstufadas'] ?? 0;
    }
    if ($idtabaco) {
    $stmtUpdate = $conecta->prepare("
        UPDATE tabaco
        SET total = ?, totalHectares = ?
        WHERE idtabaco = ?
    ");
    $stmtUpdate->bind_param("dii", $totalPes, $totalHectares, $idtabaco);
    $stmtUpdate->execute();
}
}
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
    <script src="ajaxPeriodo.js"></script>
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

    <!-- Safra -->
    <form action="cadastrarTabaco.php" method="post">
        <!-- Guardar o período selecionado -->
        <input type="hidden" name="redirect" value="tabaco.php?periodoSafra=<?= $periodoSelecionado ?>">

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
                for (let ano = 2000; ano < 2080; ano++) {
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
                <input type="number" value="<?= $totalPes ?? '' ?>" name="total" class="form-control" placeholder="total de pés"  readonly>
            </div>
            <div class="input-box">
                <label class="form-label">VALOR TOTAL DA VENDA</label>
                <input type="number" value="<?= $tabaco['precoTotal'] ?? '' ?>" name="precoTotal" class="form-control" placeholder="R$" >
            </div>
            <div class="input-box">
                <label class="form-label">TOTAL DE ESTUFADAS</label>
                <input type="number" value="<?= $tabaco['estufadas'] ?? '' ?>" name="estufadas" class="form-control" placeholder="1,2,3,4,5.....">
            </div>            
            <div class="input-box">
                <label class="form-label">QUILOS PRODUZIDOS</label>
                <input type="number" value="<?= $tabaco['kilos'] ?? '' ?>" name="kilos" class="form-control" placeholder="kg">
            </div>
            <div class="input-box">
                <label class="form-label">TOTAL DE HECTARES</label>
                <input type="number" value="<?= $totalHectares ?? '' ?>" name="totalHectares" class="form-control" placeholder="1,2,3,4,5....." readonly>
            </div>
        </div>
        <button type="submit" class="btn-default"><i class="fa-solid fa-check"></i> SALVAR DADOS DA SAFRA</button>
    </form>



    <!--  Área -->
   <form method="POST" action="cadastrarArea.php">
    <input type="hidden" name="periodoEscondido" value="<?= $periodoSelecionado ?>">
    <input type="hidden" id="produtorId" value="<?= $_SESSION['idprodutor'] ?>">
    <input type="hidden" name="idareaEscondida" value="<?= $dadosArea['idarea'] ?? '' ?>">

    <div id="input_container">
        <div class="input-box" id="campo_area">
           <label class="form-label">Adicionar área ou clique e selecione uma área:</label>
             <div id="selectAreas">
                <select name="idarea" class="form-control" onchange="window.location='tabaco.php?periodoSafra=<?= urlencode($periodoSelecionado) ?>&idarea='+this.value">
                    <option value="">Nova área:</option>
                    <?php foreach ($areasEdicao as $a): ?>
                        <option value="<?= $a['idarea'] ?>" <?= ($dadosArea && $dadosArea['idarea'] == $a['idarea']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
         </div>



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

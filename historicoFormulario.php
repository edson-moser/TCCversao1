<?php
// historicoFormulario.php
include('protect.php');
require 'conexao.php';
 require_once 'vendor/autoload.php'; // usar composer
    use Dompdf\Dompdf;
    use Dompdf\Options;

$produtor_id = $_SESSION['idprodutor'] ?? null;
$periodoSelecionado = $_GET['periodo'] ?? null;

$tabaco = [];
$areasSafra = [];
$qtdAreas = 0;
$somaMedia = 0;

if ($produtor_id && $periodoSelecionado) {
    // Buscar safra do período
    $stmt = $conecta->prepare("SELECT * FROM tabaco WHERE produtor_idprodutor = ? AND periodoSafra = ?");
    $stmt->bind_param("is", $produtor_id, $periodoSelecionado);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $tabaco = $result->fetch_assoc();

        // Buscar áreas dessa safra
        $stmtArea = $conecta->prepare("SELECT * FROM area WHERE tabaco_idtabaco = ?");
        $stmtArea->bind_param("i", $tabaco['idtabaco']);
        $stmtArea->execute();
        $resultArea = $stmtArea->get_result();
        while ($row = $resultArea->fetch_assoc()) {
            $areasSafra[] = $row;
        }

        // qtdAreas
        $stmtQtd = $conecta->prepare("SELECT COUNT(*) as total FROM area WHERE tabaco_idtabaco = ?");
        $stmtQtd->bind_param("i", $tabaco['idtabaco']);
        $stmtQtd->execute();
        $resQtd = $stmtQtd->get_result()->fetch_assoc();
        $qtdAreas = $resQtd['total'] ?? 0;

        // somaMedia
        $stmtSoma = $conecta->prepare("SELECT SUM(mediaFolhas) as soma FROM area WHERE tabaco_idtabaco = ?");
        $stmtSoma->bind_param("i", $tabaco['idtabaco']);
        $stmtSoma->execute();
        $resSoma = $stmtSoma->get_result()->fetch_assoc();
        $somaMedia = $resSoma['soma'] ?? 0;
    }
}

// Se pediu PDF, gera e envia
if (isset($_GET['pdf']) && $_GET['pdf'] == '1') {
   

    // Proteções numéricas para não quebrar por divisão por zero
    $kilos = floatval($tabaco['kilos'] ?? 0);
    $totalPes = floatval($tabaco['total'] ?? 0);
    $precoTotal = floatval($tabaco['precoTotal'] ?? 0);
    $arrobas = $kilos / 15;
    $arrobasPorMilPes = ($totalPes != 0) ? ($arrobas / ($totalPes / 1000)) : 0;
    $mediaPrecoArroba = ($arrobas != 0) ? ($precoTotal / $arrobas) : 0;
    $mediaPrecoQuilo = ($kilos != 0) ? ($precoTotal / $kilos) : 0;
    $mediaFolhasSafra = ($qtdAreas != 0) ? ($somaMedia / $qtdAreas) : 0;

    $titulo = htmlspecialchars($periodoSelecionado);
    $html = '<!doctype html><html><head><meta charset="utf-8"><title>Histórico - ' . $titulo . '</title>';
    $html .= '<style>
        body{font-family: DejaVu Sans, sans-serif; color:#333; padding:18px;}
        h2{color:#2c3e50;}
        .area-block{border:1px solid #ddd;padding:12px;border-radius:6px;margin-bottom:10px;}
        .label{font-weight:600;}
        p{margin:6px 0;}
        </style></head><body>';
    $html .= "<h2>Histórico da Safra — {$titulo}</h2>";

    if (!empty($tabaco)) {
        $html .= '<div>';
        $html .= '<p><span class="label">Total de pés:</span> ' . htmlspecialchars($tabaco['total'] ?? '') . '</p>';
        $html .= '<p><span class="label">Total de quilos:</span> ' . htmlspecialchars($tabaco['kilos'] ?? '') . '</p>';
        $html .= '<p><span class="label">Total de hectares:</span> ' . htmlspecialchars($tabaco['totalHectares'] ?? '') . '</p>';
        $html .= '<p><span class="label">Total de estufadas:</span> ' . htmlspecialchars($tabaco['estufadas'] ?? '') . '</p>';
        $html .= '<p><span class="label">Valor total de venda:</span> R$' . htmlspecialchars(number_format($precoTotal, 2, ',', '.')) . '</p>';
        $html .= '<p><span class="label">Média de Folhas da Safra:</span> ' . htmlspecialchars(number_format($mediaFolhasSafra, 2, ',', '.')) . '</p>';
        $html .= '<p><span class="label">Total de Arrobas:</span> ' . htmlspecialchars(number_format($arrobas, 2, ',', '.')) . '</p>';
        $html .= '<p><span class="label">Arrobas/mil pés:</span> ' . htmlspecialchars(number_format($arrobasPorMilPes, 2, ',', '.')) . '</p>';
        $html .= '<p><span class="label">Média de Preço por Arroba:</span> R$' . htmlspecialchars(number_format($mediaPrecoArroba, 2, ',', '.')) . '</p>';
        $html .= '<p><span class="label">Média de Preço por Quilo:</span> R$' . htmlspecialchars(number_format($mediaPrecoQuilo, 2, ',', '.')) . '</p>';
        $html .= '</div>';

        if (!empty($areasSafra)) {
            $html .= '<h3>Áreas desta safra:</h3>';
            foreach ($areasSafra as $a) {
                $html .= '<div class="area-block">';
                $html .= '<p><span class="label">Nome:</span> ' . htmlspecialchars($a['nome'] ?? '') . '</p>';
                $html .= '<p><span class="label">Qtd Pés:</span> ' . htmlspecialchars($a['qtdPes'] ?? '') . '</p>';
                $html .= '<p><span class="label">Hectares:</span> ' . htmlspecialchars($a['hectares'] ?? '') . '</p>';
                $html .= '<p><span class="label">Data de Início:</span> ' . htmlspecialchars($a['dataInicio'] ?? '') . '</p>';
                $html .= '<p><span class="label">Data de Fim:</span> ' . htmlspecialchars($a['dataFim'] ?? '') . '</p>';
                $html .= '<p><span class="label">Variedades:</span> ' . htmlspecialchars($a['variedades'] ?? '') . '</p>';
                $html .= '<p><span class="label">Produtos:</span> ' . htmlspecialchars($a['produtos'] ?? '') . '</p>';
                $html .= '<p><span class="label">Pragas:</span> ' . htmlspecialchars($a['pragasDoencas'] ?? '') . '</p>';
                $html .= '<p><span class="label">Agrotóxicos:</span> ' . htmlspecialchars($a['agrotoxicos'] ?? '') . '</p>';
                $html .= '<p><span class="label">Média de folhas:</span> ' . htmlspecialchars($a['mediaFolhas'] ?? '') . '</p>';
                $html .= '<p><span class="label">Número de Colheitas:</span> ' . htmlspecialchars($a['colheitas'] ?? '') . '</p>';
                $html .= '</div>';
            }
        }
    } else {
        $html .= '<p>Nenhuma safra encontrada para este período.</p>';
    }

    $html .= '</body></html>';

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Garantir que temos uma string
$rawPeriod = (string) ($periodoSelecionado ?? '');

// Tentar normalizar com suporte a Unicode (mantém letras, números, underline e hífen)
$safe = @preg_replace('/[^\p{L}\p{N}_-]+/u', '_', $rawPeriod);

// Se preg_replace falhar (retornar null), usa fallback ASCII
if ($safe === null) {
    $safe = preg_replace('/[^a-zA-Z0-9_-]+/', '_', $rawPeriod);
}

// remover underscores repetidos e trim
$safe = preg_replace('/_+/', '_', $safe);
$safe = trim($safe, '_');

// Se ficar vazio, usa timestamp para garantir nome válido
if ($safe === '') {
    $safe = date('Ymd_His');
}

// limitar tamanho para evitar nomes gigantes
if (function_exists('mb_substr')) {
    $safe = mb_substr($safe, 0, 64);
} else {
    $safe = substr($safe, 0, 64);
}

$filename = 'historico_safra_' . $safe . '.pdf';

    $dompdf->stream($filename, ['Attachment' => false]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Histórico da Safra <?= htmlspecialchars($periodoSelecionado) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
   
    body { 
        padding: 20px; 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        color: #333;
    }
    h2, h3 { color: #4C8F6E; margin-bottom: 20px; }
    .area-block { border: 1px solid #ddd; padding: 16px; border-radius: 10px; background: #fff; margin-bottom: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); transition: transform .15s; }
    .area-block:hover { transform: translateY(-2px); }
    .label { font-weight: 600; color: #34495e; }
    p { margin: 6px 0; }
    .mb-4 { margin-bottom: 25px; }
    @media (max-width:768px){ body{padding:10px} .area-block{padding:12px} }
    .actions { margin-bottom: 18px; }
  </style>
</head>
<body>

  <h2>Histórico da Safra — <?= htmlspecialchars($periodoSelecionado) ?></h2>

  <div class="actions">
    <?php if (!empty($tabaco)): ?>
      <a href="?periodo=<?= urlencode($periodoSelecionado) ?>&pdf=1" target="_blank" class="btn btn-primary btn-sm">Gerar PDF</a>
    <?php endif; ?>
    <a href="Historico.php" class="btn btn-secondary btn-sm">Voltar</a>
  </div>

 <?php if (!empty($tabaco)): ?>
    <div class="mb-4">
      <p><span class="label">Total de pés:</span> <?= htmlspecialchars($tabaco['total'] ?? '') ?></p>
      <p><span class="label">Total de quilos:</span> <?= htmlspecialchars($tabaco['kilos'] ?? '') ?></p>
      <p><span class="label">Total de hectares:</span> <?= htmlspecialchars($tabaco['totalHectares'] ?? '') ?></p>
      <p><span class="label">Total de estufadas:</span> <?= htmlspecialchars($tabaco['estufadas'] ?? '') ?></p>
      <p><span class="label">Valor total de venda:</span> <?= htmlspecialchars("R$" . ($tabaco['precoTotal'] ?? '')) ?></p>
      <p><span class="label">Média de Folhas da Safra:</span> <?= htmlspecialchars(($qtdAreas != 0) ? $somaMedia/$qtdAreas : 0) ?></p>
      <p><span class="label">Total de Arrobas:</span> <?= htmlspecialchars(($tabaco['kilos'] ?? 0)/15) ?></p>
      <p><span class="label">Arrobas/mil pés:</span> <?= htmlspecialchars((($tabaco['kilos'] ?? 0)/15)/(($tabaco['total'] ?? 1)/1000)) ?></p>
      <p><span class="label">Média de Preço por Arroba:</span> <?= htmlspecialchars("R$" . ((($tabaco['kilos'] ?? 0)/15) ? $tabaco['precoTotal']/($tabaco['kilos']/15) : 0)) ?></p>
      <p><span class="label">Média de Preço por Quilo:</span> <?= htmlspecialchars("R$" . ((($tabaco['kilos'] ?? 0) != 0) ? $tabaco['precoTotal']/$tabaco['kilos'] : 0)) ?></p>
    </div>

    <h3>Áreas desta safra:</h3>
    <?php if (!empty($areasSafra)): ?>
      <?php foreach ($areasSafra as $a): ?>
        <div class="area-block">
          <p><span class="label">Nome:</span> <?= htmlspecialchars($a['nome'] ?? '') ?></p>
          <p><span class="label">Qtd Pés:</span> <?= htmlspecialchars($a['qtdPes'] ?? '') ?></p>
          <p><span class="label">Hectares:</span> <?= htmlspecialchars($a['hectares'] ?? '') ?></p>
          <p><span class="label">Data de Início:</span> <?= htmlspecialchars($a['dataInicio'] ?? '') ?></p>
          <p><span class="label">Data de Fim:</span> <?= htmlspecialchars($a['dataFim'] ?? '') ?></p>
          <p><span class="label">Variedades:</span> <?= htmlspecialchars($a['variedades'] ?? '') ?></p>
          <p><span class="label">Produtos:</span> <?= htmlspecialchars($a['produtos'] ?? '') ?></p>
          <p><span class="label">Pragas:</span> <?= htmlspecialchars($a['pragasDoencas'] ?? '') ?></p>
          <p><span class="label">Agrotóxicos:</span> <?= htmlspecialchars($a['agrotoxicos'] ?? '') ?></p>
          <p><span class="label">Média de folhas:</span> <?= htmlspecialchars($a['mediaFolhas'] ?? '') ?></p>
          <p><span class="label">Número de Colheitas:</span> <?= htmlspecialchars($a['colheitas'] ?? '') ?></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info">Nenhuma safra encontrada para este período.</div>
<?php endif; ?>

</body>
</html>

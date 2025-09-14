<?php
include('protect.php');
require 'conexao.php';

$produtor_id = $_SESSION['idprodutor'] ?? null;
$periodoSelecionado = $_GET['periodo'] ?? null;

$tabaco = [];
$areasSafra = [];

if ($produtor_id && $periodoSelecionado) {
    // Buscar safra do período
    $stmt = $conecta->prepare("SELECT * FROM tabaco WHERE produtor_idprodutor = ? AND periodoSafra = ?");
    $stmt->bind_param("is", $produtor_id, $periodoSelecionado);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $tabaco = $result->fetch_assoc();

        // Buscar áreas dessa safra
        $stmtArea = $conecta->prepare("SELECT * FROM area WHERE tabaco_idtabaco = ?");
        $stmtArea->bind_param("i", $tabaco['idtabaco']);
        $stmtArea->execute();
        $resultArea = $stmtArea->get_result();
        while ($row = $resultArea->fetch_assoc()) {
            $areasSafra[] = $row;
        }
    }
    $qtdAreas = 0;
if (!empty($tabaco)) {
    $stmtQtd = $conecta->prepare("SELECT COUNT(*) as total FROM area WHERE tabaco_idtabaco = ?");
    $stmtQtd->bind_param("i", $tabaco['idtabaco']);
    $stmtQtd->execute();
    $resQtd = $stmtQtd->get_result()->fetch_assoc();
    $qtdAreas = $resQtd['total'] ?? 0;
}
// buscar soma das médias de folhas para esse período
$somaMedia = 0;
if (!empty($tabaco)) {
    $stmtSoma = $conecta->prepare("SELECT SUM(mediaFolhas) as soma FROM area WHERE tabaco_idtabaco = ?");
    $stmtSoma->bind_param("i", $tabaco['idtabaco']);
    $stmtSoma->execute();
    $resSoma = $stmtSoma->get_result()->fetch_assoc();
    $somaMedia = $resSoma['soma'] ?? 0;
}

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

  h2, h3 {
      color: #2c3e50;
      margin-bottom: 20px;
  }

  .area-block {
      border: 1px solid #ddd;
      padding: 16px;
      border-radius: 10px;
      background: #ffffff;
      margin-bottom: 15px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      transition: transform 0.2s, box-shadow 0.2s;
  }

  .area-block:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .label {
      font-weight: 600;
      color: #34495e;
  }

  p {
      margin: 6px 0;
  }

  .mb-4 {
      margin-bottom: 25px;
  }

  @media (max-width: 768px) {
      body {
          padding: 10px;
      }
      .area-block {
          padding: 12px;
      }
  }
</style>

</head>
<body>

  <h2>Histórico da Safra — <?= htmlspecialchars($periodoSelecionado) ?></h2>

 <?php if (!empty($tabaco)): ?>
    <div class="mb-4">
      <p><span class="label">Total de pés:</span> <?= htmlspecialchars($tabaco['total'] ?? '') ?></p>
      <p><span class="label">Total de quilos:</span> <?= htmlspecialchars($tabaco['kilos'] ?? '') ?></p>
      <p><span class="label">Total de hectares:</span> <?= htmlspecialchars($tabaco['totalHectares'] ?? '') ?></p>
      <p><span class="label">Total de estufadas:</span> <?= htmlspecialchars($tabaco['estufadas'] ?? '') ?></p>
      <p><span class="label">Valor total de venda:</span> <?= htmlspecialchars("R$" . ($tabaco['precoTotal'] ?? '')) ?></p>
      <p><span class="label">Média de Folhas da Safra:</span> <?= htmlspecialchars($somaMedia/$qtdAreas) ?></p>
      <p><span class="label">Total de Arrobas:</span> <?= htmlspecialchars($tabaco['kilos']/15) ?></p>
      <p><span class="label">Arrobas/mil pés:</span> <?= htmlspecialchars(($tabaco['kilos']/15)/($tabaco['total']/1000)) ?></p>
      <p><span class="label">Média de Preço por Arroba:</span> <?= htmlspecialchars("R$" . (($tabaco['kilos']/15) ? $tabaco['precoTotal']/($tabaco['kilos']/15) : 0)) ?></p>
      <p><span class="label">Média de Preço por Quilo:</span> <?= htmlspecialchars("R$" . ($tabaco['precoTotal']/$tabaco['kilos'])) ?></p>
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
<?php endif; ?>

</body>
</html>

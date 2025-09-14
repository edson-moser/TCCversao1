<?php
include('protect.php');
require 'conexao.php';

$produtor_id = $_SESSION['idprodutor'] ?? null;

$sql = "SELECT DISTINCT periodoSafra 
        FROM tabaco 
        WHERE produtor_idprodutor = ? 
          AND periodoSafra IS NOT NULL 
        ORDER BY periodoSafra ASC";

$stmt = $conecta->prepare($sql);
$stmt->bind_param("i", $produtor_id);
$stmt->execute();
$result = $stmt->get_result();



?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Históricos - Naturis</title>
  <link rel="icon" sizes="32X32" href="NaturisLogo.png">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>
  <link rel="stylesheet" href="Historico.css" />
  <script src="Historico.js" defer></script>
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
          <li class="nav-item active"><a class="nav-link" href="paginaInicial.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="tabaco.php">Tabaco</a></li>
          <li class="nav-item"><a class="nav-link" href="eucalipto.php">Eucalipto</a></li>
          <li class="nav-item"><a class="nav-link" href="clima.php">Clima</a></li>
          <li class="nav-item"><a class="nav-link" href="Historico.php">Históricos</a></li>
          <li class="nav-item"><a class="nav-link" href="produtor.php">Produtor</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <div class="tela">
    <div class="container mt-4">
      <h3>Períodos de safra cadastrados</h3>
      <table class="table table-bordered table-striped mt-3">
        <thead>
          <tr>
            <th>Período</th>
            <th>Abrir formulário</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): 
                  $periodo = $row['periodoSafra'];
                  // gera link para abrir o formulário de tabaco com o período selecionado
                  $url = 'tabaco.php?periodoSafra=' . urlencode($periodo);
          ?>
            <tr>
              <td><?php echo htmlspecialchars($periodo); ?></td>
              <td>
                <!-- abre na mesma aba -->
              <a href="historicoFormulario.php?periodo=<?= urlencode($periodo) ?>&pdf=1" target="_blank" class="btn btn-primary btn-sm">Gerar PDF</a>


                <!--
                  Se preferir abrir em nova aba, use target="_blank":
                  <a href="<?php echo $url; ?>" target="_blank" class="btn btn-success btn-sm">Abrir formulário</a>
                -->
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">Nenhum período de safra encontrado.</div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

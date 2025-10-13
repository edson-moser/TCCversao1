<?php
include('protect.php')
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="Eucalipto.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="icon" sizes="32X32" href="NaturisLogo.png">
  <title>Eucalipto</title>
</head>
<body>
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

  <div class="form-container">
    <h2 class="titulo-eucalipto">ADICIONAR INFORMAÇÕES DA PRODUÇÃO DE EUCALIPTO:</h2>
    <form id="eucalipto-form">

      <div class="input-area">
        <input type="number" id="input1" placeholder="Quantidade de pés" min="0" step="1">
        <select id="select1" onchange="changeType('input1', 'select1')">
          <option value="number">Por Hectare</option>
          <option value="number">Por metro quadrado</option>
        </select>
      </div>

      <div class="input-area">
        <input type="number" id="input2" placeholder="Tamanho da Área" min="0" step="0.01">
        <select id="select2" onchange="changeType('input2', 'select2')">
          <option value="number">Por Hectare</option>
          <option value="number">Por metro quadrado</option>
        </select>
      </div>

      <div class="input-area">
        <!-- campo único de data de plantio -->
        <input type="date" id="inputDate" placeholder="Data de plantio" aria-label="Data de plantio">
      </div>

      <div class="confirm-button">
        <button type="submit">Confirmar</button>
      </div>
    </form>
  </div>

  <div class="grafico">
    <canvas id="eucaliptoChart" class="line-chart" width="400" height="200"></canvas>

    <?php
    // pega o id do produtor da sessão — ajuste a chave se necessário
    $produtorId = $_SESSION['id'] ?? $_SESSION['user_id'] ?? null;
    ?>

    <script>
      const produtorId = <?php echo json_encode($produtorId); ?>;
    </script>

    <script src="Eucalipto.js"></script>
  </div>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
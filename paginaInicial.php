<?php
require 'conexao.php';
//require 'protect.php';

$idProdutor = $_SESSION['idprodutor'];

$sql = "SELECT * FROM listatarefas WHERE produtor_idprodutor = ?";
$stmt = $conecta->prepare($sql);
$stmt->bind_param("i", $idProdutor);
$stmt->execute();
$result = $stmt->get_result();

$tarefas = [];
while ($row = $result->fetch_assoc()) {
    $tarefas[] = $row;
}

$total = count($tarefas);
$concluidas = count(array_filter($tarefas, fn($t) => $t['conclusao']));
$progresso = $total > 0 ? ($concluidas / $total) * 100 : 0;
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Naturis</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="paginaInicial.css">
   
    <link rel="icon" sizes="32X32" href="NaturisLogo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <!-- <script src="paginaInicial.js"></script> -->
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
                <li class="nav-item">
                  <a class="nav-link" >Bem-vindo produtor(a), <?php echo $_SESSION['nome']?>!!</a>
                </li>
              </ul>
        </div>
    </nav>

   

    <!--Carrosel-->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
    
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="foto1altura2.png" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="foto2.png" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="foto03.png" alt="Third slide">
            </div>
        </div>
    
        <!-- Controles de navegação DENTRO do carrossel -->
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="registro-container">
  <h1>Saldo da Safra</h1>
  <div class="registro-grid">
    <!-- Coluna de Inserção e Filtros -->
    <div class="registro-left">
      <h3>Adicionar Valor</h3>
      <div class="registro-form">
        <input type="number" id="input-valor" placeholder="Valor a ser inserido" />
        <select id="input-tipo">
          <option value="positivo">Valor Positivo |+|</option>
          <option value="negativo">Valor Negativo |-|</option>
        </select>
        <input type="text" id="input-descricao" placeholder="Descrição do valor" />
        <input type="date" id="input-data" />
        <select id="input-cultura">
          <option value="ambos">Ambos</option>
          <option value="eucalipto">Eucalipto</option>
          <option value="tabaco">Tabaco</option>
        </select>
        <select id="input-categoria">
          <option value="insumos">Insumos</option>
          <option value="empregados">Empregados</option>
          <option value="maquinario">Maquinário</option>
          <option value="outro">Outro</option>
        </select>
        <button onclick="adicionarItemRegistro()">Adicionar</button>
      </div>

      <h3>Filtrar por Data</h3>
      <div class="filtro-data">
        <label>De:</label>
        <input type="date" id="filtro-data-inicio" />
        <label>Até:</label>
        <input type="date" id="filtro-data-fim" />
        <label>Cultura:</label>
        <select id="filtro-cultura">
          <option value="todos">Todos</option>
          <option value="eucalipto">Eucalipto</option>
          <option value="tabaco">Tabaco</option>
        </select>
        <button onclick="filtrarPorData()">Aplicar Filtro</button>
        <button onclick="limparFiltro()">Limpar Filtro</button>
      </div>
    </div>

    <!-- Coluna de Resultado e Histórico -->
    <div class="registro-right">
      <div class="painel-saldo">
        <h3>Resumo</h3>
        <p>Saldo Total: <span id="saldo-total">R$ 0,00</span></p>
        <p>Saldo Eucalipto: <span id="saldo-eucalipto">R$ 0,00</span></p>
        <p>Saldo Tabaco: <span id="saldo-tabaco">R$ 0,00</span></p>
      </div>

      <h3>Histórico</h3>
      <ul id="lista-registro"></ul>
    </div>
  </div>
</div>
      

<!-- Lista de Tarefas -->
<div id="lista" class="listaTarefas">
  <section class="containerTarefas">
    <h1>Lista de Tarefas</h1>

    <!-- Adicionar tarefa -->
    <form method="post" action="cadastrarTarefa.php" class="add-task-form" id="taskInputContainer">
      <input type="text" name="descricao" placeholder="Digite a tarefa" id="taskInput" required>
      <button type="submit" title="Adicionar tarefa">+</button>
    </form>

    <!-- Lista -->
    <ul id="taskList">
      <?php foreach ($tarefas as $tarefa): ?>
        <li class="task-item">
          <?php if (isset($_GET['editar']) && $_GET['id'] == $tarefa['idlistaTarefas']): ?>
            <form method="post" action="cadastrarTarefa.php?id=<?= $tarefa['idlistaTarefas'] ?>&salvar=1" class="edit-form">
              <input type="text" name="nova_descricao" value="<?= htmlspecialchars($tarefa['descricao']) ?>" required>
              <button type="submit" class="save-btn">✔</button>
              <a href="paginaInicial.php#lista" class="cancel-btn">✖</a>
            </form>
          <?php else: ?>
            <span class="<?= $tarefa['conclusao'] ? 'completed-task' : '' ?>">
              <?= htmlspecialchars($tarefa['descricao']) ?>
            </span>
            <span class="action-icons">
              <a href="paginaInicial.php?editar=1&id=<?= $tarefa['idlistaTarefas'] ?>#lista" title="Editar">✎</a>
              <a href="cadastrarTarefa.php?excluir=1&id=<?= $tarefa['idlistaTarefas'] ?>" onclick="return confirm('Deseja excluir esta tarefa?');" title="Excluir">🗑</a>
              <a href="cadastrarTarefa.php?toggle=1&id=<?= $tarefa['idlistaTarefas'] ?>" title="Concluir ou desmarcar">
                <?= $tarefa['conclusao'] ? '✖' : '✔' ?>
              </a>
            </span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Barra de Progresso -->
    <div class="progress-container">
      <div class="progress-bar" style="width: <?= $progresso ?>%;"></div>
    </div>
  </section>
</div>


<!-- Quem somos nós -->
<div id="quemSomos">
    <footer class="quem-somos">
        <h2>Quem Somos Nós:</h2>
        <div class="container">
            <div class="pessoa">
                <img src="imagemEdson.jpeg" alt="Foto do Criador 1">
                <h3>Edson Moser</h3>
                <p>Email: <a href="mailto:edsonmoser97@gmail.com">edsonmoser97@gmail.com</a></p>
            </div>
            <div class="pessoa">
                <img src="imagemMiguel.jpeg" alt="Foto do Criador 2">
                <h3>Miguel Franz Marchi</h3>
                <p>Email: <a href="mailto:miguelfmarchi@gmail.com">miguelfmarchi@gmail.com</a></p>
            </div>
        </div>
        <p class="descricao">
            Facilitamos a gestão da agricultura familiar, conectando produtores a ferramentas para um cultivo sustentável e eficiente.
        </p>
    </footer>
</div>

</body>

</html>
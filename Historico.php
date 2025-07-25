<?php

//include('protect.php')
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Históricos- Naturis</title>
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

      <div class="tela">
        <div class="container">
      
          <form id="invoice-form">
            <table>
              <thead>
                <tr>
                  <th>Período</th>
                  <!-- <th>Ano</th> -->
                  <th>Documento de Histórico</th>
                </tr>
              </thead>
              <tbody id="invoice-body">
                <tr>
                  <td></td>
                  <td></td>
                  <td>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
      

</body>
</html>
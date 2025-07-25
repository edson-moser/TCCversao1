<?php

//include('protect.php')
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Clima- Naturis</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
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
  <link rel="stylesheet" href="Clima.css" />
  <script src="Clima.js" defer></script>
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

  <div class="main-content">
    <div class="container">
      <div class="form">
        <h3>Confira o clima de uma cidade:</h3>
        <div class="form-input-container">
          <input type="text" placeholder="Digite o nome da cidade" id="city-input" />
          <button id="search">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>
      </div>
      <div id="weather-data" class="hide">
        <h2><i class="fa-solid fa-location-dot"></i> <span id="city"></span> <img id="country"></img></h2>
        <p id="temperature"><span></span>&deg;C</p>
        <div id="description-container">
          <p id="description"></p>
          <img id="weather-icon" src="" alt="Condições atuais">
        </div>
        <div id="details-container">
          <p id="umidity">
            <i class="fa-solid fa-droplet"></i>
            <span></span>
          </p>
          <p id="wind">
            <i class="fa-solid fa-wind"></i>
            <span></span>
          </p>
        </div>
      </div>
      <div id="error-message" class="hide">
        <p>Não foi possível encontrar o clima de uma cidade com este nome.</p>
      </div>
      <div id="loader" class="hide">
        <i class="fa-solid fa-spinner"></i>
      </div>
      <div id="suggestions">
        <button id="Ibirama">Ibirama</button>
        <button id="José Boiteux">José Boiteux</button>
        <button id="Presidente Getulio">Presidente Getúlio</button>
        <button id="Apiuna">Apiuna</button>
        <button id="Rio do sul">Rio do Sul</button>
        <button id="Dona emma">Dona Emma</button>
        <button id="Witmarsum">Witmarsum</button>
        <button id="vitor meireles">Vitor Meireles</button>
      </div>
    </div>
  </div>
</body>

</html>
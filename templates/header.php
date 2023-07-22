<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data["headerTitle"] ?></title>

    <!-- Police d'écriture -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet">


    <!-- CSS Bootstrap et CSS-->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/MarMovies/css/main.css">

</head>

<body>
<header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between">
            <div class="container-fluid">

               

                <!-- Logo et nom du site -->
                <img src="/MarMovies/img/logo.png" alt="" class="logo">
                <a class="navbar-brand" href="./">
                    Mar Movies
                </a>

                 <!-- Bouton hamburger visible sur mobile et tablette -->
                 <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu de navigation -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Mes films</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">À propos</a>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </nav>
    </header>






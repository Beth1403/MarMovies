<?php
// Récupérer la clé API TMDb dans le dossier config
require_once('./config/config.php');
$apiKey = $config['api_key'];

// Fonction pour effectuer une requête à l'API TMDb
function callTMDBAPI($endpoint, $params = array())
{
    global $apiKey;
    $url = "https://api.themoviedb.org/3/{$endpoint}?api_key={$apiKey}&" . http_build_query($params);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);

    if ($response === false) {
        // erreur de communication avec l'API
        echo "Erreur de communication avec l'API TMDB.";
        return false;
    }

    $data = json_decode($response, true);
    return $data;
}

// Fonction pour obtenir les credits d'un film
function getMovieCredits($movieId)
{
    return callTMDBAPI('movie/' . $movieId . '/credits');
}

// Fonction pour obtenir le poster d'un film
function getMoviePosterURL($movieId)
{
    $movieDetails = callTMDBAPI('movie/' . $movieId);

    $posterUrl = null;

    if (isset($movieDetails['poster_path'])) {
        $posterUrl = 'https://image.tmdb.org/t/p/w500/' . $movieDetails['poster_path'];
    }

    return $posterUrl;
}


// Recherche de films
if (isset($_GET['query'])) {
    $query = urlencode($_GET['query']);

    // Effectuer la recherche en utilisant l'API TMDB
    $searchResult = callTMDBAPI('search/movie', array('query' => $query));

    // Vérifier si la requête a réussi et s'il y a des résultats
    if ($searchResult && isset($searchResult['results']) && count($searchResult['results']) > 0) {
        $movies = $searchResult['results'];

        // Créer un tableau pour stocker les IDs des films trouvés
        $movieIds = array();
        foreach ($movies as $movie) {
            $movieIds[] = $movie['id'];
        }
    } else {
        $errorMessage = "Aucun résultat trouvé pour la recherche : " . urldecode($query);
    
    }
} else {
        // Code pour afficher un fond d'écran générique lorsqu'aucune recherche n'est effectuée
        $backgroundImage = '/MarMovies/img/cinema.jpg';
    }

        
    
        
// <!-- header -->
$data = array("headerTitle" => "Mar Movies");
include("templates/header.php");
?>

<!-- Background image : poster du premier film trouvé ou photo cinéma vide si aucune recherche n'est faite -->
<body style="background-image: url('<?php echo (isset($movies) && count($movies) > 0) ? getMoviePosterURL($movies[0]['id']) : $backgroundImage; ?>'); background-position: center; background-color: rgba(0, 0, 0, 0.4);">




<div class="container mt-5">

    <div class="row">

        <div class="col">

            <h1 class="mb-4">Rechercher un film</h1>

            <form action="index.php" method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Entrez le titre du film" required>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-custom">Rechercher</button>
                    </div>
                </div>
            </form>

            <!-- Carrousel -->
            <div id="movieCarousel" class="carousel slide carousel-fade" data-interval="false">
                    <div class="carousel-inner">
                        <?php if (isset($movies)) : ?>
                            <?php $carouselItemIndex = 0; ?>
                            <?php foreach ($movies as $index => $movie) : ?>
                                <div class="carousel-item <?php echo ($carouselItemIndex === 0) ? 'active' : ''; ?>">
                                    <div class="card mb-3">
                                        <div class="row no-gutters">
                                            <div class="col-md-4">
                                                <?php
                                                // Poster available?
                                                if (isset($movie['poster_path'])) {
                                                    $posterUrl = 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'];
                                                ?>
                                                    <img src="<?php echo $posterUrl; ?>" alt="<?php echo $movie['title']; ?> Poster" class="img-fluid">
                                                <?php } else { ?>
                                                    <h3>Image non disponible</h3>
                                                <?php } ?>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h3 class="card-title"><?php echo $movie['title']; ?></h3>
                                                    <p class="card-text">
                                                        <small class="text-muted">
                                                            <?php
                                                            // Format date en français
                                                            $releaseDate = $movie['release_date'];
                                                            $formattedDate = date('d/m/Y', strtotime($releaseDate));
                                                            echo $formattedDate;
                                                            ?>
                                                        </small>
                                                    </p>

                                                    <?php
                                                    // Obtenir les movie credits
                                                    $movieCredits = getMovieCredits($movie['id']);

                                                    // Trouver le réalisateur
                                                    $director = null;
                                                    foreach ($movieCredits['crew'] as $crewMember) {
                                                        if ($crewMember['job'] == 'Director') {
                                                            $director = $crewMember['name'];
                                                            break; // Stop la boucle quand le réal est trouvé
                                                        }
                                                    }
                                                    ?>

                                                    <?php if (isset($director)) : ?>
                                                        <p class="card-text">Réalisateur : <?php echo $director; ?></p>
                                                    <?php endif; ?>

                                                    <p class="card-text"><?php echo $movie['overview']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $carouselItemIndex++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($movies)) : ?>
                    <a class="carousel-control-prev" href="#movieCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Précédent</span>
                    </a>
                    <a class="carousel-control-next" href="#movieCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Suivant</span>
                    </a>
                    <?php endif; ?>
                </div>

                <?php if (isset($errorMessage)) : ?>
                    <p><?php echo $errorMessage; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

        

<!-- ... footer ... -->
<?php include("templates/footer.php"); ?>
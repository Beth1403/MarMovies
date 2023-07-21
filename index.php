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

// Fonction pour obtenir le trailer d'un film
function getMovieTrailerURL($movieId)
{
    $videos = callTMDBAPI('movie/' . $movieId . '/videos');
    $trailerUrl = null;

    if (isset($videos['results']) && is_array($videos['results'])) {
        foreach ($videos['results'] as $video) {
            if ($video['type'] === 'Trailer') {
                $trailerUrl = 'https://www.youtube.com/embed/' . $video['key'];
                break;
            }
        }
    }

    return $trailerUrl;
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
}
// <!-- header -->
$data = array("headerTitle" => "Mar Movies");
include("templates/header.php");
?>

     <!-- Trailer en background -->
     <div id="trailerBackground">
        <iframe id="trailerIframe" src="" frameborder="0" allowfullscreen loop style="position: absolute; top: 0; left: 0;"></iframe>
    </div>

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
                <div id="movieCarousel" class="carousel slide">
                    <div class="carousel-inner">
                        <?php if (isset($movies)) : ?>
                            <?php $carouselItemIndex = 0; ?>
                            <?php foreach ($movies as $index => $movie) : ?>
                                <?php $trailerUrl = getMovieTrailerURL($movie['id']); ?>
                                <?php $carouselItemClass = ($carouselItemIndex === 0) ? 'carousel-item active' : 'carousel-item'; ?>
                                <div class="<?php echo $carouselItemClass; ?>">
                                    <div class="card mb-3">
                                        <div class="row no-gutters">
                                            <div class="col-md-4">
                                                <?php
                                                // poster disponible ?
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
                                                            // Change le format de la date
                                                            $releaseDate = $movie['release_date'];
                                                            $formattedDate = date('d/m/Y', strtotime($releaseDate));
                                                            echo $formattedDate;
                                                            ?>
                                                        </small>
                                                    </p>

                                                    <?php
                                                    // obtenir les movie credits
                                                    $movieCredits = getMovieCredits($movie['id']);

                                                    // trouver le réalisateur
                                                    $director = null;
                                                    foreach ($movieCredits['crew'] as $crewMember) {
                                                        if ($crewMember['job'] == 'Director') {
                                                            $director = $crewMember['name'];
                                                            break; // Stop la boucle lorsqu'il est trouvé
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
                    <a class="carousel-control-prev" href="#movieCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Précédent</span>
                    </a>
                    <a class="carousel-control-next" href="#movieCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Suivant</span>
                    </a>
                </div>

                <?php if (isset($errorMessage)) : ?>
                    <p><?php echo $errorMessage; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ... footer ... -->
    <?php include("templates/footer.php"); ?>

    

   
   
    

   

    





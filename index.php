<?php
// Récupérer la clé API TMDb dans le dossier config
require_once('./config/config.php');
$apiKey = $config['api_key'];

// Fonction pour effectuer une requête à l'API TMDb
function callTMDBAPI($endpoint, $params = array()) {
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

// Recherche de films
if (isset($_GET['query'])) {
    $query = urlencode($_GET['query']);

    // Effectuer la recherche en utilisant l'API TMDB
    $searchResult = callTMDBAPI('search/movie', array('query' => $query));

    // Vérifier si la requête a réussi et s'il y a des résultats
    if ($searchResult && isset($searchResult['results']) && count($searchResult['results']) > 0) {
        $movies = $searchResult['results'];
        $firstMovieId = $movies[0]['id'];
    } else {
        $errorMessage = "Aucun résultat trouvé pour la recherche : " . urldecode($query);
    }
    if (isset($firstMovieId)) {
        // Effectuer une nouvelle requête pour obtenir les détails complets du film
        $movieDetails = callTMDBAPI('movie/'.$firstMovieId);
        // echo '<pre>' , var_dump($movies[0]) , '</pre>';
        
        
    
       // Fonction pour obtenir les credits d'un film
function getMovieCredits($movieId) {
    return callTMDBAPI('movie/' . $movieId . '/credits');
}



if (isset($firstMovieId)) {
    // Obtenir les détails d'un film
    $movieDetails = callTMDBAPI('movie/' . $firstMovieId);

    
    $movieCredits = getMovieCredits($firstMovieId);

    // Vérifier si les détails et les crédits du filmsont présents
    if ($movieDetails && $movieCredits) {
        $movieTitle = $movieDetails['title'];
        $releaseDate = $movieDetails['release_date'];
        $overview = $movieDetails['overview'];
        

        // Chercher le réalisateur dans les crédits
        $director = null;
        foreach ($movieCredits['crew'] as $crewMember) {
            if ($crewMember['job'] == 'Director') {
                $director = $crewMember['name'];
                break; // Stop la boucle une fois le réalisateur trouvé 
            }
        }
    }
}

        
    }}

$data = array("headerTitle" => "MarMovies");
include("templates/header.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mar Movies</title>
    <!-- Inclure ici vos fichiers CSS -->
</head>
<body>
    <h1>Rechercher un film</h1>
    <form action="index.php" method="GET">
        <input type="text" name="query" placeholder="Entrez le titre du film" required>
        <button type="submit">Rechercher</button>
    </form>

    <?php if (isset($movieTitle)): ?>
    <h2>Détails du film :</h2>
    <?php
    // Vérifier si l'image de l'affiche est disponible
    if (isset($movieDetails['poster_path'])) {
        $posterUrl = 'https://image.tmdb.org/t/p/w500' . $movieDetails['poster_path'];
        ?>
        <img src="<?php echo $posterUrl; ?>" alt="<?php echo $movieTitle; ?> Poster">
    <?php } ?>
    <h3> <?php echo $movieTitle; ?> </h3>
    <h3> (<?php echo $releaseDate; ?>) </h3>
    <?php if (isset($director)): ?>
        <p>Réalisateur : <?php echo $director; ?></p>
    <?php endif; ?>
    <p>Synopsis : <?php echo $overview; ?></p>
<?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <p><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <!-- Inclure ici vos fichiers JavaScript -->
    <?php
include("templates/footer.php");
?>
</body>
</html>

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

// Fonction pour obtenir les credits d'un film
function getMovieCredits($movieId) {
    return callTMDBAPI('movie/' . $movieId . '/credits');
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

$data = array("headerTitle" => "MarMovies");
include("templates/header.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mar Movies</title>
    <!-- CSS -->
</head>
<body>
    <h1>Rechercher un film</h1>
    
    <form action="index.php" method="GET">
        <input type="text" name="query" placeholder="Entrez le titre du film" required>
        <button type="submit">Rechercher</button>
    </form>

    <?php if (isset($movies)): ?>
        <h2>Résultats de la recherche :</h2>
        <?php foreach ($movies as $movie): ?>
            <h3><?php echo $movie['title']; ?></h3>
            <h3>(<?php echo $movie['release_date']; ?>)</h3>

            <?php
            // Vérifier si l'image de l'affiche est disponible
            if (isset($movie['poster_path'])) {
                $posterUrl = 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'];
                ?>
                <img src="<?php echo $posterUrl; ?>" alt="<?php echo $movie['title']; ?> Poster">
            <?php } ?>

            <?php
            // Obtenir les crédits du film
            $movieCredits = getMovieCredits($movie['id']);
            
            // Chercher le réalisateur dans les crédits
            $director = null;
            foreach ($movieCredits['crew'] as $crewMember) {
                if ($crewMember['job'] == 'Director') {
                    $director = $crewMember['name'];
                    break; // Stop la boucle une fois le réalisateur trouvé 
                }
            }
            ?>

            <?php if (isset($director)): ?>
                <p>Réalisateur : <?php echo $director; ?></p>
            <?php endif; ?>

            <p> <?php echo $movie['overview']; ?> </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($errorMessage)): ?>
        <p><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <!-- JavaScript -->
    <?php include("templates/footer.php"); ?>
</body>
</html>

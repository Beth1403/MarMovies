"# MarMovies"

Fonctionnement de l'app : 
- Header qui par un clic sur le titre, réinitialise et ramène à la page d'accueil
- Barre de recherche où entrer le titre du film recherché
- Bouton rechercher ou entrée pour lancer la recherche
- Résultats présentés sous forme de carrousel, du plus pertinent (exactitude avec le mot-clé recherché) au moins pertinent.
- Affichage du poster, du titre, de la date de sortie, du réalisateur et du synopsis.

  Limitations :
  - J'ai voulu faire en sorte que le background affiche le trailer du film visible sur la page. Puis j'ai vu les problèmes de performance que ça engendrait (pas terrible pour une candidature chez Lemon, où performance durable est le maître mot) et j'ai abandonné l'idée, mais le code est dans Github, sous le commit "trailer background".
  - Je n'ai pas eu le temps de gérer l'accessibilité, je sais que mes choix de couleurs de police peuvent être améliorés.
  - De même, je n'ai pas eu le temps de m'intéresser à la sécurisation de ma clé API.
  - Le menu de navigation est uniquement décoratif.
 
  Choix de conception : 
  - J'ai fait le choix d'afficher tous les films comportant le mot-clé recherché, ma première version de code ne montrait que le premier (et plus pertinent). Néanmoins, il faudrait des fonctionnalités supplémentaires, comme un filtre par genre pour avoir un outil de recherche vraiment efficace.
  - J'ai choisi de mettre en place un carrousel car on est sur un site à but informatif uniquement, et je trouvais intéressant d'avoir toutes les informations nécessaires sur un seul viewport, sans besoin de scroll.
  - Par un ternaire, j'affiche en fond d'écran soit une image générique (pop-corn) soit l'affiche du premier film trouvé.
 
  Merci de m'avoir lue !


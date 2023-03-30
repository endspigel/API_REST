<?php
// Inclure la bibliothèque pour générer les JWT
require('jwt_utils.php');

function isValidUser($username, $password){
    // Connexion à la base de données MySQL avec PDO
    $pdo = new PDO('mysql:host=localhost;dbname=api-auth;=utf8mb4', 'root', '');

    // Affiche les erreurs lors de l'exécution des requêtes SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation de la requête SQL pour récupérer l'utilisateur avec le nom d'utilisateur fourni
    $stmt = $pdo->prepare('SELECT * FROM utilisateur WHERE login = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Récupération de l'utilisateur correspondant
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification si l'utilisateur existe et si le mot de passe fourni correspond au mot de passe stocké en base de données
    if ($user && $password === $user['mdp']) {
        return true;
    } else {
        return false;
    }
}

// Vérifie les identifiants envoyés via POST et renvoie un jeton JWT si les identifiants sont valides
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login']) && isset($_POST['mdp'])) {
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];
    
    if (isValidUser($login, $mdp)){
        // Vérification du rôle de l'utilisateur
        $user['role'] = $_SESSION['role'];

        $jwt_headers = array('typ' => 'JWT', 'alg' => 'HS256');
        $jwt_payload = array('login' => $login, 'exp' => time() + 60); // expire dans une minute
        $jwt = generate_jwt($jwt_headers, $jwt_payload);

        // Stockage du jeton JWT dans une variable de session
        $_SESSION['token'] = $jwt;

        // Redirection vers la page de profil de l'utilisateur
        header('Location: serveurREST.php');
        exit();
    } else {
        http_response_code(401); // Non autorisé
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}

// Vérifie si un jeton JWT est valide
if (isset($_SESSION['token']) && is_jwt_valid($_SESSION['token'])){
    // Récupération des données de l'utilisateur depuis le jeton JWT
    $jwt_data = decode_jwt($_SESSION['token']);

    // Affichage de la page de profil de l'utilisateur
    echo "Bienvenue, " . $jwt_data['login'] . " !";
    // ...
} else {
    // Affiche la page de connexion si le formulaire n'a pas été envoyé ou si le jeton est invalide
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Page de connexion</title>
    </head>
    <body>
        <h1>Page de connexion</h1>
        <form method="post" action="authAPI.php">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="login" name="login"><br><br>
            <label for="password">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp"><br><br>
            <input type="submit" value="Connexion">
        </form>
    </body>
    </html>
<<<<<<< Updated upstream

<?php  }

$database = new PDO('mysql:host=localhost;dbname=api-auth;=utf8mb4', 'root', '');

$requete = $database->prepare('SELECT contenu FROM article');
$requete->execute();

while($result = $requete->fetch()):
    echo $result['contenu'];

endwhile;

?>

=======
    <?php
}
?>

>>>>>>> Stashed changes

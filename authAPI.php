<?php

// Inclure la bibliothèque pour générer les JWT
require('jwt_utils.php');

// Connexion à la base de données MySQL avec PDO
$pdo = new PDO('mysql:host=localhost;dbname=api-auth;charset=utf8mb4', 'root', '');

// Affiche les erreurs lors de l'exécution des requêtes SQL
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Vérifie les identifiants envoyés via POST et renvoie un jeton JWT si les identifiants sont valides
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $posted_data = (array) json_decode(file_get_contents('php://input'), TRUE);

    $login = ($posted_data['login']);
    $mdp = ($posted_data['mdp']);

    // Préparation de la requête SQL pour récupérer l'utilisateur avec le nom d'utilisateur fourni
    $stmt = $pdo->prepare('SELECT * FROM Utilisateur WHERE login = :login');

    $stmt->execute(array('login' => $login));

    // Récupération de l'utilisateur correspondant
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification si l'utilisateur existe et si le mot de passe fourni correspond au mot de passe stocké en base de données
    if (!empty($user) && $mdp === $user['mdp']) {
        // Récupération du rôle de l'utilisateur
        $stmt = $pdo->prepare('SELECT nom FROM Role WHERE id = :id');
        $stmt->execute(array('id' => $user['id']));
        $role = $stmt->fetch(PDO::FETCH_ASSOC)['nom'];
    
        // Génération du jeton JWT
        $jwt_headers = array('typ' => 'JWT', 'alg' => 'HS256');
        $jwt_payload = array('login' => $login, 'role' => $role, 'exp' => time() + 3600); // expire dans une heure
        $jwt = generate_jwt($jwt_headers, $jwt_payload);
    
        // Envoi de la réponse avec le jeton JWT
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('jwt' => $jwt));
        
    } else {
        // Envoi d'une réponse d'erreur si les identifiants sont invalides
        http_response_code(401); // Non autorisé
        echo json_encode(array('message' => 'Identifiants invalides.'));
    }
    
} else {
    // Envoi d'une réponse d'erreur pour les requêtes autres que POST
    http_response_code(405); // Méthode non autorisée
    echo json_encode(array('message' => 'Méthode non autorisée.'));
}
?>

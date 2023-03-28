<?php
    // Inclure la bibliothèque pour générer les JWT
    require('jwt_utils.php');

    function isValidUser($username, $password){
        // Connexion à la base de données MySQL avec PDO
    $pdo = new PDO('mysql:host=localhost;dbname=my_database;charset=utf8mb4', 'username', 'password');

    // Préparation de la requête SQL pour récupérer l'utilisateur avec le nom d'utilisateur fourni
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Récupération de l'utilisateur correspondant
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification si l'utilisateur existe et si le mot de passe fourni correspond au mot de passe stocké en base de données
    if ($user && password_verify($password, $user['password'])) {
        return true;
    } else {
        return false;
    }
    }
    

    $data = (array) json_decode(file_get_contents('php://input'), TRUE);

    // MODIF Vérifie les identifiants envoyés via POST et renvoie un jeton JWT si les identifiants sont valides
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['username']) && isset($data['password'])) {
        $username = $data['username'];
        $password = $data['password'];

        if (isValidUser($username, $password)){
            $jwt_headers = array('typ' => 'JWT', 'alg' => 'HS256');
            $jwt_payload = array('username' => $username, 'exp' => time() + 60); // expire dans une minute
            $jwt = generate_jwt($jwt_headers, $jwt_payload);

            header('Content-Type: application/json');
            echo json_encode(array('token' => $jwt));
        } else {
            http_response_code(401); // Non autorisé
        }
    }

    if (is_jwt_valid($jwt)){
        http_response_code(200);
    }
?>
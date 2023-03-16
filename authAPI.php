<?php
    // Inclure la bibliothèque pour générer les JWT
    require('jwt_utils.php');

    // Identifiants en dur pour les tests
    $valid_credentials = array(
        'username' => 'johndoe',
        'password' => 'password123'
    );

    function isValidUser($username, $password){
        global $valid_credentials;
        return $username === $valid_credentials['username'] && $password === $valid_credentials['password'];
    }

    $data = (array) json_decode(file_get_contents('php://input'), TRUE);

    // Vérifie les identifiants envoyés via POST et renvoie un jeton JWT si les identifiants sont valides
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
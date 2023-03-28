<?php
    require('jwt_utils.php');

    $jwt = get_jwt_from_request(); // fonction qui extrait le jeton JWT de la requête


    //C'est pas bon car on a le cas où l'utilisateur n'est pas authentifié 
    if ($jwt === null) {
        // Jeton JWT manquant dans la requête
        http_response_code(401); // Non autorisé
        echo 'Authentification requise pour accéder à cette page.';
        exit();
    }
    
    $jwt_payload = (array) jwt_decode($jwt);
    $user_id = $jwt_payload['user_id'];

    // Interroger la base de données pour récupérer le rôle de l'utilisateur
    $db = new PDO('mysql:host=localhost;dbname=api-auth', 'root', '');
    $stmt = $db->prepare('SELECT nom FROM Role WHERE id = (SELECT id FROM Utilisateur WHERE login = :login)');
    $stmt->bindParam(':login', $user_id);
    $stmt->execute();
    $user_role = $stmt->fetchColumn();

    $http_method = $_SERVER['REQUEST_METHOD'];
    

    switch($user_role){
        case('Moderator'):
            // GET tous les articles
            // GET informations sur tous les articles
            // DELETE : supprimer n'importe quel article
            switch ($http_method){
                case "GET":
                    $query = "SELECT * FROM article";
                    $select = $database->prepare($query);
                    $select->execute(array());
                    $data = $select -> fetchAll();
                    break;
                case "DELETE":
                    if(!empty($_GET['id'])){
                        $id = htmlspecialchars($_GET['id_article']);
                        $query = "DELETE FROM article WHERE id_article=$id";
                        $delete = $database->prepare($query);
                        $delete ->execute(array());
                    }

                    
            }
            break;
        case('Publisher'):
            // POST publier un nouvel article
            // GET consulter un article
            // GET consulter tous les articles
            // MODIFY : modifier un des articles de l'utilisateur
            // Delete : supprimer un de ses articles
            switch($http_method){
                case "GET":
                    $query = "SELECT * FROM article";
                    $select = $database->prepare($query);
                    $select->execute(array());
                    $data = $select -> fetchAll();
                    break;
                case "POST":
                    $query = "INSERT INTO article"
            }
            break;
        default:
            // Cas de l'utilisateur non authentifié
            // GET tous les articles sans les informations

    }
?>

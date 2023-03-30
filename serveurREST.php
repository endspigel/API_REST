<?php
require('jwt_utils.php');

$database = new PDO('mysql:host=localhost;dbname=api-auth;=utf8mb4', 'root', '');

$requete = $database->prepare('SELECT date_publication, contenu, login FROM article');
$requete->execute();

$jwt = get_bearer_token(); // fonction qui extrait le jeton JWT de la requête

// Vérification de l'authentification
if ($jwt === null) {
    http_response_code(401); // Non autorisé
    echo json_encode(array("message" => "Authentification requise pour accéder à cette page."));
    exit();
}

// Récupération du login de l'utilisateur
$jwt_payload = (array) jwt_decode($jwt);
$login = $jwt_payload['login'];

// Récupération du rôle de l'utilisateur
$stmt = $database->prepare('SELECT nom FROM Role WHERE id = (SELECT id FROM Utilisateur WHERE login = :login)');
$stmt->bindParam(':login', $login);
$stmt->execute();
$user_role = $stmt->fetchColumn();

// Récupération de la méthode HTTP utilisée
$http_method = $_SERVER['REQUEST_METHOD'];

// Vérification du rôle de l'utilisateur et traitement en conséquence
switch($user_role){
    case('Moderator'):
        // GET tous les articles
        // GET informations sur un article
        // DELETE : supprimer n'importe quel article
        switch ($http_method){
            case "GET":
                $query = "SELECT * FROM article";
                $select = $database->prepare($query);
                $select->execute(array());
                $data = $select -> fetchAll();
                echo json_encode($data);
                break;
            case "DELETE":
                if(!empty($_GET['id_article'])){
                    $id = htmlspecialchars($_GET['id_article']);
                    $query = "DELETE FROM article WHERE id_article=$id";
                    $delete = $database->prepare($query);
                    $delete ->execute(array());
                    http_response_code(204); // No Content
                } else {
                    http_response_code(400); // Bad Request
                    echo json_encode(array("message" => "L'identifiant de l'article est manquant."));
                }
                break;
            default:
                http_response_code(405); // Method Not Allowed
                echo json_encode(array("message" => "Méthode HTTP non autorisée."));
        }
        break;
    case('Publisher'):
        // POST publier un nouvel article
        // GET consulter un article
        // GET consulter tous les articles
        // MODIFY : modifier un des articles de l'utilisateur
        // DELETE : supprimer un de ses articles
        switch($http_method){
            case "GET":
                if(!empty($_GET['id_article'])){
                    $id = htmlspecialchars($_GET['id_article']);
                    $query = "SELECT * FROM article WHERE id_article = $id";
                    $select = $database->prepare($query);
                    $select->execute(array());
                    $data = $select -> fetchAll();
                    if(count($data) == 0){
                        http_response_code(404); // Not Found
                        echo json_encode(array("message" => "Article non trouvé."));
                    }
                    else{
                        echo json_encode($data);
                    }
                } else {
                    $query = "SELECT * FROM article";
                    $select = $database->prepare($query);
                    $select->execute(array());
                    $data = $select -> fetchAll();
                    echo json_encode($data);
                }
                break;
            case "POST":
                $json = file_get_contents('php://input');
                $article = json_decode($json, true);
                $contenu = $article['contenu'];
                $date_publication = date('Y-m-d H:i:s');
                $query = "INSERT INTO article (date_publication, contenu, login) VALUES (:date_publication, :contenu, :login)";
                $stmt = $database->prepare($query);
                $stmt->bindParam(':date_publication', $date_publication);
                $stmt->bindParam(':contenu', $contenu);
                $stmt->bindParam(':login', $login);
                $stmt->execute();
                http_response_code(201); // Created
                break;
            case "PUT":
                if(!empty($_GET['id_article'])){
                    $id = htmlspecialchars($_GET['id_article']);
                    $json = file_get_contents('php://input');
                    $article = json_decode($json, true);
                    $contenu = $article['contenu'];
                    $query = "UPDATE article SET contenu=:contenu WHERE id_article=$id AND login=:login";
                    $stmt = $database->prepare($query);
                    $stmt->bindParam(':contenu', $contenu);
                    $stmt->bindParam(':login', $login);
                    $stmt->execute();
                    http_response_code(204); // No Content
                } else {
                    http_response_code(400); // Bad Request
                    echo json_encode(array("message" => "L'identifiant de l'article est manquant."));
                }
                break;
            case "DELETE":
                if(!empty($_GET['id_article'])){
                    $id = htmlspecialchars($_GET['id_article']);
                    $query = "DELETE FROM article WHERE id_article=$id AND login=:login";
                    $delete = $database->prepare($query);
                    $delete ->bindParam(':login', $login);
                    $delete ->execute(array());
                    http_response_code(204); // No Content
                } else {
                    http_response_code(400); // Bad Request
                    echo json_encode(array("message" => "L'identifiant de l'article est manquant."));
                }
                break;
            default:
                http_response_code(405); // Method Not Allowed
                echo json_encode(array("message" => "Méthode HTTP non autorisée."));
        }
        break;
    default:
        http_response_code(403); // Forbidden
        echo json_encode(array("message" => "Vous n'avez pas les droits nécessaires pour accéder à cette ressource."));
    }

<?php
require('jwt_utils.php');

$database = new PDO('mysql:host=localhost;dbname=api-auth;=utf8mb4', 'root', '');

$requete = $database->prepare('SELECT date_publication, contenu, login FROM article');
$requete->execute();

$jwt = get_bearer_token(); // fonction qui extrait le jeton JWT de la requête

// Vérification de l'authentification
if (is_jwt_valid($jwt)) {
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
                if(!empty($_GET['id_article'])){
                    $reponse = getById($id);
                } else {
                    $response = getAllDataForModePubli();
                }
                deliver_response(200, "Votre message", $reponse);
                break;
            case "DELETE":
                if(!empty($_GET['id_article'])){
                    $id = htmlspecialchars($_GET['id_article']);
                    $response = deletePhrase($id);
                    deliver_response(204, "Article bien supprimé", $reponse);
                } else {
                    deliver_response(400, "L'identifiant de l'article est manquant", $reponse);
                }
                break;
            default:
                http_response_code(405); // Method Not Allowed
                echo json_encode(array("message" => "Méthode HTTP non autorisée."));
        }
        break;
    case('Publisher'):
        // AJOUTER LE GET POUR QU4IL VOIT SES ARTICLES A LUI
        // POST publier un nouvel article
        // GET consulter un article
        // GET consulter tous les articles
        // MODIFY : modifier un des articles de l'utilisateur
        // DELETE : supprimer un de ses articles
        switch($http_method){
            case "GET":
                if(!empty($_GET['id_article'])){
                    $reponse = getById($id);
                } else {
                    $response = getAllDataForModePubli();
                }
                deliver_response(200, "Votre message", $reponse);
                break;
            case "POST":
                $json = file_get_contents('php://input');
                $article = json_decode($json, true);
                $contenu = $article['contenu'];
                if (!empty($contenu)){
                    $reponse=postNewArticle($contenu);
                }
                break;
            case "PUT":
                if(!empty($_GET['id_article'])){
                    $id = htmlspecialchars($_GET['id_article']);
                    $json = file_get_contents('php://input');
                    $article = json_decode($json, true);
                    $contenu = $article['contenu'];
                    $reponse = putPhrase($id, $contenu);
                    deliver_response(200, "Votre message", $reponse);
                } else {
                    echo json_encode(array("message" => "L'identifiant de l'article est manquant."));
                }
                break;
            case "DELETE":
                if(!empty($_GET['id_article'])){
                    $id = htmlspecialchars($_GET['id_article']);
                    $reponse = deletePhrase($id);
                    deliver_response(204, "La phrase a bien été supprimé", $response);
                } else {
                    deliver_response(400, "L'identifiant de l'article est manquant.", null);
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

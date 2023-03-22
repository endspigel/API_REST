<?php
    require_once 'config.php';
    require("functions.php");
    require('authAPI.php')

    // Vérification du jeton
    $jwt = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;
    if (!is_jwt_valid($jwt)){
        http_response_code(401); // Non autorisé
        die();
    }
    

    /// Paramétrage de l'entête HTTP (pour la réponse au Client)
    header("Content-Type:application/json");

    /// Identification du type de méthode HTTP envoyée par le client
    $http_method = $_SERVER['REQUEST_METHOD'];

    switch ($http_method){
        /// Cas de la méthode GET
        case "GET" :
            $query = "SELECT * FROM article";
            $select = $database->prepare($query);
            $select->execute(array());
            $data = $select -> fetchAll();
            echo "OK";
            deliver_response(201, "REUSSITK", $data);
            break;
        /// Cas de la méthode POST
        case "POST" :
                $postedData = json_decode(file_get_contents('php://input'));
                $query = "INSERT INTO chuckn_facts(phrase,vote,date_ajout,date_modif,faute,signalement)VALUES(?,?,?,?,?,?)";

                $maData = (array)$postedData;
                $phrase = $maData['phrase'];
                $vote = $maData['vote'];
                $date_ajout = $maData['date_ajout'];
                $date_modif = $maData['date_modif'];
                $faute = $maData['faute'];
                $signalement = $maData['signalement'];

                $insert = $database->prepare($query);
                $insert->execute(array($phrase,$vote,$date_ajout,$date_modif,$faute,$signalement));

                deliver_response(201, "[LOCAL SERVEUR REST] POST REQUEST : Insert Data OK", NULL);            
            
            break;
        /// Cas de la méthode PUT
        case "PUT" :
            /// Récupération des données envoyées par le Client
            $postedData = json_decode(file_get_contents('php://input'));
            $maData = (array)$postedData;
            $id = $maData['id'];
            $phrase = $maData['phrase'];
            $vote = $maData['vote'];
            $date_ajout = $maData['date_ajout'];
            $date_modif = $maData['date_modif'];
            $faute = $maData['faute'];
            $signalement = $maData['signalement'];

            $query = "UPDATE chuckn_facts SET phrase=?,vote=?,date_ajout=?,date_modif=?,faute=?,signalement=? WHERE id=?";
            $update = $database -> prepare($query);
            $update -> execute(array($phrase,$vote,$date_ajout,$date_modif,$faute,$signalement,$id));
            deliver_response(200, "[LOCAL SERVEUR REST] PUT REQUEST : UPDATE Data OK", NULL);
            break;
        
        case "DELETE":
            if(!empty($_GET['id'])){
                $id = htmlspecialchars($_GET['id']);
                $query = "DELETE FROM chuckn_facts WHERE id=$id";
                $delete = $database->prepare($query);
                $delete ->execute(array());
            }
            deliver_response(200, "[LOCAL SERVEUR REST] DELETE REQUEST : DELETE Data OK", NULL);

        default:
            //pas de default
            break;
    }
?>

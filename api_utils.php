<?php

function connexionBD(){
        $db_username = 'root';
        $db_password = '';
        $db_name = 'api-auth';
        $db_host = '127.0.0.1:3306';

        try {
            $linkpdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_password);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage()); 
        }
        return $linkpdo;
    }

    //Sélection par un id
    function getById($id){
        $database = connexionBD();
        $query = "SELECT * FROM article WHERE id_article=?";
        $select = $database->prepare($query);
        $select->execute(array($id));
        $data = $select -> fetchAll();
        return $data;
    }

    //Sélection totale
    function getAllDataForAnonymous(){
        $database = connexionBD();
        $query = "SELECT contenu FROM article";
        $select = $database->prepare($query);
        $select->execute(array());
        $data = $select -> fetchAll();
        return $data;
    }

    //Sélection totale
    function getAllDataForModePubli(){
        $database = connexionBD();
        $query = "SELECT * FROM article";
        $select = $database->prepare($query);
        $select->execute(array());
        $data = $select -> fetchAll();
        return $data;
    }

    //Ajout d'une phrase
    function postNewArticle($contenu){
        $database = connexionBD();
        $date_publication = date('Y-m-d H:i:s');
        $query = "INSERT INTO api-auth(date_publication, contenu)VALUES(?, ?)";
        $insert = $database -> prepare($query);
        $insert -> execute($date_publication, $contenu);
    }

    //Modification d'une phrase
    function putPhrase($id, $contenu){
        $database = connexionBD();
        $query = "UPDATE article SET contenu = ? WHERE id_article = ?";
        $insert = $database->prepare($query);
        $insert->execute(array($phrase, $id));
    }

    function deletePhrase($id){
        $database = connexionBD();
        $query = "DELETE FROM article WHERE id_article=?";
        $insert = $database -> prepare($query);
        $insert -> execute($id);
    }

    // Récupération du rôle de l'utilisateur
    function getRole($id){
        $database = connexionBD();
        $stmt = $database->prepare('SELECT nom FROM Role WHERE id = (SELECT id FROM Utilisateur WHERE login = :login)');
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $user_role = $stmt->fetchColumn();
        return $user_role;
    }
?>
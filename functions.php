<?php
    function useAPI($methode,$URL){
        $result = file_get_contents("$URL",false,stream_context_create(array('http' => array('method' => "$methode"))));
        $resultat = (array)json_decode($result);
        switch($methode){
            case "GET":
                $data = (array)$resultat['data'];
            break;
        }
        return $data;
    }
    function connexionBD(){
        $db_username = 'root';
        $db_password = '';
        $db_name = 'chknrs';
        $db_host = '127.0.0.1:3306';

        try {
            $linkpdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=UTF8", $db_username, $db_password);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage()); 
        }
        return $linkpdo;
    }

    //Sélection totale
    function getAllData(){
        $database = connexionBD();
        $query = "SELECT * FROM chuckn_facts";
        $select = $database->prepare($query);
        $select->execute(array());
        $data = $select -> fetchAll();
        return $data;
    }

    //Sélection par un id
    function getById($id){
        $database = connexionBD();
        $query = "SELECT * FROM chuckn_facts WHERE id=?";
        $select = $database->prepare($query);
        $select->execute(array($id));
        $data = $select -> fetchAll();
        return $data;
    }

    //Ajout d'une phrase
    function postNewPhrase($phrase){
        $database = connexionBD();
        $query = "INSERT INTO chuckn_facts(phrase)VALUES(?,?,?,?,?,?)";
        $insert = $database -> prepare($query);
        $insert -> execute($phrase);
    }

    //Modification d'une phrase
    function putPhrase($id, $phrase){
        $database = connexionBD();
        $query = "UPDATE chuckn_facts SET phrase = ? WHERE id = ?";
        $insert = $database->prepare($query);
        $insert->execute(array($phrase, $id));
    }

    function deletePhrase($id){
        $database = connexionBD();
        $query = "DELETE FROM chuckn_facts WHERE id=$id";
        $insert = $database -> prepare($query);
        $insert -> execute($id);
    }


?>
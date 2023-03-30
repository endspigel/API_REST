<?php
    require('config.php');

    if(isset($_POST['contenu'])){
        $date_publication = date('Y-m-d H:i:s');
        $contenu = $_POST['contenu'];
        $login = 'user1'; // à remplacer par le login de l'utilisateur authentifié

        $requete = $linkpdo->prepare('INSERT INTO article (date_publication, contenu, login) VALUES(:date_publication, :contenu, :login)');
        $requete->bindParam(':date_publication', $date_publication);
        $requete->bindParam(':contenu', $contenu);
        $requete->bindParam(':login', $login);
        $requete->execute();
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
</head>
<body>
    <h1>Ajouter un article</h1>
    <form method="post" action="insertArticle.php">
        <label for="contenu">Contenu :</label>
        <input type="text" id="contenu" name="contenu"><br><br>
        <input type="submit" value="Publier">
    </form>
</body>
</html>

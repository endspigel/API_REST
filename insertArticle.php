<?php 
    require('config.php');

    $requete = $linkpdo->prepare('INSERT INTO contenu VALUES(:date_publication, :contenu');
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
            <label for="contenu">contenu :</label>
            <input type="text" id="contenu" name="contenu"><br><br>
            <input type="submit" value="Publier">
        </form>
    </body>
    </html>

<?php 

    $date_publication = date('d-m-y h:i:s');
    //$contenu = $_POST['contenu'];

    $requete->bindParam(':date_publication', $date_publication);
    $requete->bindParam(':contenu', $_POST['contenu']);
    // $requete->bindParam(':login', $login);

    $requete->execute();
?>
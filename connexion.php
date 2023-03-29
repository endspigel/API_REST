<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page de connexion</title>
</head>
<body>
    <h1>Page de connexion</h1>
    <form method="post" action="auth.php">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>

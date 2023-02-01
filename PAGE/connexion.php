<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300&display=swap" rel="stylesheet">
    <title>Connexion</title>
</head>


<body>

    <?php
    session_start(["user"]);
    ?>

    <form class="form" method="post" action="/TRAITEMENT/connexion.php">

        <div class="sign">

            <div class="input-element">
                <label for="email"></label>
                <input type="email" placeholder="Email" name="email" id="email" 
                value="<?php if (isset($_SESSION["saisie"])) {echo $_SESSION["saisie"]["email"];} ?>">
                <?php
                if (!empty($_SESSION["error"]["email"])) {
                    echo $_SESSION["error"]["email"];
                    unset($_SESSION["error"]["email"]);
                }
                ?>
            </div>

            <div class="input-element">
                <label for="pass"></label>
                <input type="password" placeholder="Mot de passe" name="password" id="pass">
                <?php
                if (!empty($_SESSION["error"]["password"])) {
                    echo $_SESSION["error"]["password"];
                    unset($_SESSION["error"]["password"]);
                }
                ?>

            </div>

            <div class="submit">
                <input class="btn-sign" id="connexion" type="submit" name="registre" value="Me connecter">
                <?php
                if (!empty($_SESSION["error"]["vide"])) {
                    echo $_SESSION["error"]["vide"];
                    unset($_SESSION["error"]["vide"]);
                }
                if (!empty($_SESSION["error"]["user"])) {
                    echo $_SESSION["error"]["user"];
                    unset($_SESSION["error"]["user"]);
                }
                ?>
                <a href="inscription.php">Pas encore inscrit ?</a>
            </div>
        </div>

    </form>

</body>

</html>
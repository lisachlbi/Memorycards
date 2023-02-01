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
    <title>Inscription</title>
</head>


<body>

    <?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start(["user"]);
    }else{
        header("location:/PAGE/connexion.php");
    }
    ?>
    <form class="form" method="post" action="/TRAITEMENT/inscription.php">

        <div class="sign">

            <div class="input-element">
                <input type="email" placeholder="Email" name="email" id="email" 
                value="<?php if (isset($_SESSION["saisie"])) echo $_SESSION["saisie"]["email"] ?>">
                <?php
                if (!empty($_SESSION["error"]["email"])) {
                    echo $_SESSION["error"]["email"];
                    unset($_SESSION["error"]["email"]);
                }
                ?>
            </div>

            <div class="input-element">
                <input type="text" placeholder="Pseudo" name="pseudo" id="pseudo" 
                value="<?php if (isset($_SESSION["saisie"])) echo $_SESSION["saisie"]["pseudo"] ?>">
                <?php
                if (!empty($_SESSION["error"]["pseudo"])) {
                    echo $_SESSION["error"]["pseudo"];
                    unset($_SESSION["error"]["pseudo"]);
                }
                ?>
            </div>

            <?php unset($_SESSION["saisie"]); ?>

            <div class="input-element">
                <input type="password" placeholder="Mot de passe" name="password" id="pass">
                <?php
                if (!empty($_SESSION["error"]["password"])) {
                    echo $_SESSION["error"]["password"];
                    unset($_SESSION["error"]["password"]);
                }
                ?>
            </div>

            <div class="input-element">
                <input type="password" placeholder="Confirmer le mot de passe" name="confirmPassword" id="confirmPassword">
                <?php
                if (!empty($_SESSION["error"]["confirmPassword"])) {
                    echo $_SESSION["error"]["confirmPassword"];
                    unset($_SESSION["error"]["confirmPassword"]);
                }
                ?>
            </div>

            <div class="submit">
                <input class="btn-sign" id="inscription" required type="submit" name="registre" value="M'inscrire">
                <?php
                if (!empty($_SESSION["error"]["vide"])) {
                    echo $_SESSION["error"]["vide"];
                    unset($_SESSION["error"]["vide"]);
                }
                ?>
                <a href="connexion.php">Déjà un compte ?</a>
            </div>

        </div>
    </form>

</body>

</html>
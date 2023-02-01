<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(["user"]);
}
require_once '../CONFIG/config.php';
require_once '../CONFIG/PDO.php';

// Vérification du formulaire est envoyé

if (isset($_POST["registre"])) {

    //* On filtre les saisie et verifier qu'elles sont remplies, sinon on envoie un message d'erreur en session
    unset($_SESSION["error"]);
    $_SESSION["error"] = [];

    $empty = false;

    if (empty($_POST["email"])) {
        $empty = true;
        $_SESSION["error"]["email"] = "<div class='error'>L'email est requis</div>";
        header("location:/PAGE/connexion.php");
    } else {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $empty = true;
            $_SESSION["error"]["email"] = "<div class='error'>L'email n'a pas le bon format</div>";
            header("location:/PAGE/connexion.php");
        };
    };

    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);

    if (empty($_POST["password"])) {
        $empty = true;
        $_SESSION["error"]["password"] = "<div class='error'>Le mot de passe est requis</div>";
        header("location:/PAGE/connexion.php");
    } else {
        if ((strlen($_POST["password"]) < 8)) {
            $empty = true;
            $_SESSION["error"]["password"] = "<div class='error'>Minimun 8 charactères</div>";
            header("location:/PAGE/connexion.php");
        };
    };

    // Connexion à la base de données

    $sql = "SELECT * FROM utilisateur WHERE email = :email";

    $requete = $db->prepare($sql);
    $requete->bindValue(":email", $email);
    $requete->execute();

    $user = $requete->fetch(PDO::FETCH_BOTH);

    //Si l'utilisateur n'existe pas

    if (!$user) {
        $empty = true;
        $_SESSION["error"]["user"] = "<div class='error'>L'utilisateur ou le mot de passe est incorrect</div>";
        header("location:/PAGE/connexion.php");
    }

    // Si user existant, vérifier le mot de passe

    if (!password_verify($_POST["password"], $user["password"])) {
        $empty = true;
        $_SESSION["error"]["user"] = "<div class='error'>L'utilisateur ou le mot de passe est incorrect</div>";
        header("location:/PAGE/connexion.php");
    } else {

        // L'utilisateur et le mot de passe sont correctes : On ouvre la session
        // On stocke dans $_SESSION les informations de l'utilisateur

        $_SESSION["user"] = array(
            "pseudo" => $user["pseudo"],
            "email" => $user["email"],
            "id_utilisateur" => $user["id_utilisateur"],
        );

        // On redirige vers acceuil
        header("Location:/PAGE/accueil.php");
    }

    if ($empty == true) {
        $empty = true;
        $_SESSION["error"]["vide"] = "<div class='error'>Veuillez remplir les champs</div>";
        header("Location:/PAGE/connexion.php");
    };

    // s'il y a des erreurs, on veut garder ce que l'utilisateur a saisie
    if (!empty($_SESSION["error"])) {
        // s'il y a des erreurs, on veut garder ce que l'utilisateur a saisie
        $_SESSION['saisie'] = $_POST;
    }
}

//* S'il y a des erreurs on redirige
else {

    header("location:/PAGE/connexion.php");
}

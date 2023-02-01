<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
} else {
    header("location:/PAGE/connexion.php");
};
require_once '../CONFIG/config.php';
require_once '../CONFIG/PDO.php';

//* On vérifie si les champs ont été correctement remplis

if (isset($_POST["registre"])) {

    unset($_SESSION["error"]);
    $_SESSION["error"] = [];

    $empty = false;

    //* Si les champs sont vides ont envoie un message stocké en session
    if (empty($_POST["email"])) {
        $empty = true;
        $_SESSION["error"]["email"] = "<div class='error'>L'email est requis</div>";
        header("location:/PAGE/inscription.php");
    } else {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $_SESSION["error"]["email"] = "<div class='error'>L'email n'a pas le bon format</div>";
            header("location:/PAGE/inscription.php");
        };
    };

    if (empty($_POST["pseudo"])) {
        $_SESSION["error"]["pseudo"] = "<div class='error'>Le pseudo est requis</div>";
        header("location:/PAGE/inscription.php");
    };

    if (empty($_POST["password"])) {
        $empty = true;
        $_SESSION["error"]["password"] = "<div class='error'>Le mot de passe est requis</div>";
        header("location:/PAGE/inscription.php");
    } else {
        //* On demande un minimun de longueur pour le mot de passe
        if ((strlen($_POST["password"]) < 8)) {
            $_SESSION["error"]["password"] = "<div class='error'>Minimun 8 charactères</div>";
            header("location:/PAGE/inscription.php");
        };
    };

    if (empty($_POST["confirmPassword"])) {
        $empty = true;
        $_SESSION["error"]["confirmPassword"] = "<div class='error'>Le mot de passe est requis</div>";
        header("location:/PAGE/inscription.php");
    } else {
        if (($_POST["password"] !== $_POST["confirmPassword"])) {
            $empty = true;
            $_SESSION["error"]["confirmPassword"] = "<div class='error'>Le mot de passe ne correspond pas</div>";
            header("location:/PAGE/inscription.php");
        };
    };

    // On vérifie s'il y a eu des erreurs => on quitte
    if (!empty($_SESSION["error"])) {
        // s'il y a des erreurs, on veut garder ce que l'utilisateur a saisie
        $_SESSION['saisie'] = $_POST;
    }

    // Recupération des utilisateurs
    $sql = "SELECT * FROM utilisateur";
    $requete = $db->prepare($sql);
    $requete->execute();

    $users = $requete->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($_POST)) {

        // Nettoyage de l'email 
        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

        // Protection des données
        $pseudo = strip_tags($_POST["pseudo"]);

        // Hacher le mot de passe
        $password = $_POST["password"];

        $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

        // Si le mail existe déjà
        try {
            $sql = "INSERT INTO utilisateur ( email, password, pseudo) VALUES ( :email, :password, :pseudo)";
            $requete = $db->prepare($sql);
            $requete->execute(["email" => $email, "password" => $pass, "pseudo" => $pseudo]);
        } catch (PDOException $error) {
            if ($error->getCode() === '23000') {
                $_SESSION["error"]["duplicateEmail"] = "<div class='error'>Minimun 8 charactères</div>";
                header("location:/PAGE/inscription.php");
            }
        }

        //* Sinon on redirige
    } else {
        header("location:/PAGE/inscription.php");
    }

    //* Si les saisies du formulaire sont vides on envoie un message
    if ($empty == true) {
        $_SESSION["error"]["vide"] = "<div class='error'>Veuillez remplir les champs</div>";
        header("location:/PAGE/inscription.php");
        die();
    };

    //* Si les saisies sont incorrectes on redirige
    $_SESSION['saisie']['email'] = $email;
    header("location:/PAGE/connexion.php");
} else {
    header("location:/PAGE/inscription.php");
}

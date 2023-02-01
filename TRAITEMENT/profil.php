<?php

session_start(["user"]);
if (empty($_SESSION)) {
    header("location:/PAGE/connexion.php");
};
include_once('../PAGE/nav.php');
require_once '../CONFIG/config.php';
require_once '../CONFIG/PDO.php';

if (isset($_POST["ajouter"])) {

    unset($_SESSION["error"]);
    $_SESSION["error"] = [];

    $empty = false;

    // Protection des données

    $idUtilisateur = $_SESSION["user"]["id_utilisateur"];

    $pseudo = strip_tags($_POST["pseudo"]);

    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    $password = $_POST["password"];
    $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $confirmPassword = $_POST["confirmPassword"];
    $pass = password_hash($_POST["confirmPassword"], PASSWORD_DEFAULT);


    if (!empty($_POST["pseudo"])) {

        if ($pseudo != $_SESSION["user"]["pseudo"]) {

            $sql = "SELECT COUNT(pseudo) FROM utilisateur WHERE pseudo = :pseudo AND id_utilisateur != :id_utilisateur";
            $requete = $db->prepare($sql);
            $requete->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
            $requete->bindParam(":id_utilisateur", $idUtilisateur);
            $requete->execute();

            $nombrePseudo = $requete->fetch(PDO::FETCH_BOTH);

            if ($nombrePseudo[0] == 0) {
                $updatePseudo = "UPDATE utilisateur SET pseudo = :pseudo WHERE id_utilisateur = :id_utilisateur";
                $requete = $db->prepare($updatePseudo);
                $requete->execute([
                    "id_utilisateur" => $idUtilisateur,
                    "pseudo" => $pseudo,
                ]);
            } else {
                $empty = true;
                $_SESSION["error"]["pseudo"] = "<div style='color: black; class='error'>Ce pseudo existe déjà</div>";
                header("location:/PAGE/profil.php");
            }
            header("location:/PAGE/profil.php");
        }
        header("location:/PAGE/profil.php");
    }


    if (empty($_POST["email"])) {

        if ($email != $_SESSION["user"]["email"]) {

            $sql = "SELECT COUNT(email) FROM utilisateur WHERE email = :email AND id_utilisateur != :id_utilisateur";
            $requete = $db->prepare($sql);
            $requete->bindParam(":id_utilisateur", $idUtilisateur, PDO::PARAM_INT);
            $requete->bindParam(":email", $email, PDO::PARAM_STR);
            $requete->execute();

            $nombreEmail = $requete->fetch(PDO::FETCH_BOTH);

            if ($nombreEmail[0] == 0) {

                $updateEmail = "UPDATE utilisateur SET email = :email WHERE id_utilisateur = :id_utilisateur";
                $requete = $db->prepare($updateEmail);
                $requete->execute([
                    "id_utilisateur" => $idUtilisateur,
                    "email" => $email,
                ]);
                header("location:/PAGE/profil.php");
            } else {
                $empty = true;
                $_SESSION["error"]["email"] = "<div style='color: black; class='error'>Cet email existe déjà</div>";
                header("Location:/PAGE/profil.php");
            }
            header("location:/PAGE/profil.php");
        }
        header("location:/PAGE/profil.php");
    }


    if ((strlen($_POST["password"]) < 8)) {
        $empty = true;
        $_SESSION["error"]["password"] = "<div class='error'>Minimun 8 charactères</div>";
        header("location:/PAGE/profil.php");
    };


    if (($_POST["password"] == $_POST["confirmPassword"]) && !empty ($_POST["password"])) {
        $updatePassword = "UPDATE utilisateur SET password = :password WHERE id_utilisateur = :id_utilisateur";
        $requete = $db->prepare($updatePassword);
        $requete->execute([
            "id_utilisateur" => $idUtilisateur,
            "password" => $pass
        ]);
        header("Location:/PAGE/profil.php");
    } else {
        $empty = true;
        $_SESSION["error"]["confirmPassword"] = "<div class='error'>Le mot de passe ne correspond pas</div>";
        header("location:/PAGE/inscription.php");
    }

    header("location:/PAGE/profil.php");
} else {
    header("location:/PAGE/profil.php");
}

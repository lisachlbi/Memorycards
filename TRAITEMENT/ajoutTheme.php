<?php
session_start(["user"]);
if (empty($_SESSION)) {
    header("location:/PAGE/connexion.php");
};
include_once('../PAGE/nav.php');
require_once '../CONFIG/config.php';
require_once '../CONFIG/PDO.php';


if (!empty($_GET['id_categorie'])) {
    $id_categorie = $_GET['id_categorie'];
} else {
    $id_categorie = $_POST['select_categorie'];
}

//* On verifie que le champs est remplis
if (isset($_POST["ajouter"])) {

    unset($_SESSION["error"]);
    $_SESSION["error"] = [];

    $empty = false;

    //* On stocke en Session un message d'erreur si la saisie est vide
    if (empty($_POST["titre"])) {
        $empty = true;
        $_SESSION["error"]["titre"] = "<div style='color: black;'class='error'>Le titre est requis</div>";
        header("location:/PAGE/themes.php");
    };

    if (empty($_POST["description"])) {
        $empty = true;
        $_SESSION["error"]["description"] = "<div style='color: black;'class='error'>La description est requise</div>";
        header("location:/PAGE/themes.php");
    }

    if (!isset($_POST["select_categorie"])) {
        $empty = true;
        $_SESSION["error"]["select-categorie"] = "<div style='color: black;'class='error'>La catégorie est requise</div>";
        header("location:/PAGE/themes.php");
    }

    // Vérification des champs requis 
    if (!empty($_POST["titre"]) && !empty($_POST["description"]) && !empty($_POST["themes_choose"])) {

        $user = $_SESSION["user"]["id_utilisateur"];

        $nomTheme = strip_tags($_POST["titre"], FILTER_SANITIZE_SPECIAL_CHARS);

        $description = strip_tags($_POST["description"], FILTER_SANITIZE_SPECIAL_CHARS);

        $public = $_POST['themes_choose'] === "public" ? 1 : 0;

        //date_creation 
        $dateCreation = date('Y-m-d');

        try {
            $sql = "INSERT INTO theme (id_utilisateur, nom_theme, description, public, id_categorie, date_creation) VALUES (:id_utilisateur, :nom_theme, :description, :public, :id_categorie, :date_creation)";

            $requete = $db->prepare($sql);
            $requete->execute([
                "id_utilisateur" => $user,
                "nom_theme" => $nomTheme,
                "description" => $description,
                "public" => $public,
                "date_creation" => $dateCreation,
                "id_categorie" => $id_categorie
            ]);
        } catch (PDOException $error) {
            if ($error->getCode() === '23000') {
                $_SESSION["error"]["duplicateTitre"] = "<div style='margin-left: 50px; color: black; class='error'>Ce nom existe déjà</div>";
                header("location:/PAGE/ajoutTheme.php");
            }
        }

        // $ajoutTheme = $requete->fetchAll(PDO::FETCH_ASSOC);
        header("location:/PAGE/themes.php");
    }

    if (!empty($_SESSION["error"])) {
        // s'il y a des erreurs, on veut garder ce que l'utilisateur a saisie
        $_SESSION['saisie'] = $_POST;
    }
} else {
    header("location:/PAGE/themes.php");
};

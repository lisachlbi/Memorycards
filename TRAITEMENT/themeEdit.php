<?php

session_start();
if (empty($_SESSION)) {
    header("location:/PAGE/connexion.php");
};
include_once('../nav.php');
require_once '../CONFIG/config.php';
require_once '../CONFIG/PDO.php';


if (isset($_POST["ajouter"])) {

    unset($_SESSION["error"]);
    $_SESSION["error"] = [];

    $empty = false;

    if (empty($_POST["titre"])) {
        $empty = true;
        $_SESSION["error"]["titre"] = "<div style='color: black; class='error'>Le titre est requis</div>";
        header("location: /PAGE/themesEdit.php?id_theme=" . $_GET['id_theme']);
    }

    if (empty($_POST["description"])) {
        $empty = true;
        $_SESSION["error"]["description"] = "<div style='color: black; class='error'>La description est requise</div>";
        header("location: /PAGE/themesEdit.php?id_theme=" . $_GET['id_theme']);
    }


    if (!empty($_SESSION["error"])) {
        // s'il y a des erreurs, on veut garder ce que l'utilisateur a saisie
        $_SESSION['saisie'] = $_POST;
    }

    if (!empty($_POST["titre"]) && !empty($_POST["description"]) && !empty($_POST["themes-choose"])) {

        $user = $_SESSION["user"]["id_utilisateur"];
    
        $nomTheme = strip_tags($_POST["titre"], FILTER_SANITIZE_SPECIAL_CHARS);
    
        $description = strip_tags($_POST["description"], FILTER_SANITIZE_SPECIAL_CHARS);
    
        $public = $_POST['themes_choose'] === "public" ? 1 : 0;
    
        $idTheme = $_GET["id_theme"];
    
        $sql = "UPDATE theme SET id_utilisateur = :id_utilisateur, nom_theme = :nom_theme, description = :description, public = :public WHERE id_theme = :id_theme";
        
        $requete = $db->prepare($sql);
        $requete->execute([
            "id_utilisateur" => $user,
            "nom_theme" => $nomTheme,
            "description" => $description,
            "public" => $public,
            "id_theme" => $idTheme,
        ]);
        header("location:/PAGE/themes.php");
    }

} else {
    header("location: /PAGE/themesEdit.php?id_theme=" . $_GET['id_theme']);
}

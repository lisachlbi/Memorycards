<?php

session_start(["user"]);
if (empty($_SESSION)) {
    header("location:/PAGE/connexion.php");
};
include_once('../PAGE/nav.php');
require_once '../CONFIG/config.php';
require_once '../CONFIG/PDO.php';

//* On verifie que le champs est remplis
if (isset($_POST["ajouter"])) {

    unset($_SESSION["error"]);
    $_SESSION["error"] = [];

    $empty = false;

    //* On stocke en Session un message d'erreur si la saisie est vide
    if (empty($_POST["nom-categorie"])) {
        $empty = true;
        $_SESSION["error"]["nom-categorie"] = "<div style='color: black;'class='error'>Le nom est requis</div>";
        header("location:/PAGE/categories.php");
        exit();
    } 

    if (!empty($_POST["ajouter"])) {

        // Protection des données
        $nom = strip_tags($_POST["nom-categorie"]);

        //* On insert dans la base de donné
        try {
            $sql = "INSERT INTO categorie (nom) VALUES (:nom)";

            $requete = $db->prepare($sql);
            $requete->bindValue(':nom', $nom, PDO::PARAM_STR);
            $requete->execute();
            header("location:/PAGE/categories.php");

            //* On envoie un message d'erreur en Session si le nom existe déjà dans la base de données    
        } catch (PDOException $error) {
            if ($error->getCode() === '23000') {
                $_SESSION["error"]["duplicateNom"] = "<div style='margin-left: 50px; color: black; class='error'>Ce nom existe déjà</div>";
                header("location:/PAGE/categories.php");
            } 
        }
    } 

    if (!empty($_SESSION["error"])) {
        // s'il y a des erreurs, on veut garder ce que l'utilisateur a saisie
        $_SESSION['saisie'] = $_POST;
    }

} else {
    header("location:/PAGE/categories.php");
}
    

<?php
session_start();
if (empty($_SESSION)) {
    header("location:/PAGE/connexion.php");
};
include_once('../nav.php');
require_once '../CONFIG/config.php';
require_once '../CONFIG/PDO.php';

//* On vérifie si l'id du thème correspond à un theme créé par l'utilisateur connecter et on supprime
if (!empty($_GET["id_theme"])) {
    $id = filter_var($_GET["id_theme"], FILTER_VALIDATE_INT);

    if($id !== false) {
        session_start();
        $idUser = $_SESSION["user"]["id_utilisateur"];

        $sql = "DELETE FROM theme WHERE id_theme = :id_theme AND id_utilisateur = :id_utilisateur";

        $requete = $db->prepare($sql);
        $requete -> bindParam(":id_theme", $id, PDO::PARAM_INT);
        $requete -> bindParam(":id_utilisateur", $idUser, PDO::PARAM_INT);
        $requete-> execute();
        header("location:/PAGE/themes.php");
    }
} else{
    header("location:/PAGE/themes.php");
}


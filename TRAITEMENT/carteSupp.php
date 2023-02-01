<?php
session_start(["user"]);
if (empty($_SESSION["user"])) {
    header("location:/PAGE/connexion.php");
};
include_once('../PAGE/nav.php');
require_once '../CONFIG/config.php';
require_once '../CONFIG/PDO.php';

//* On vérifie si l'id du thème correspond à un theme créé par l'utilisateur connecter et on supprime
if (!empty($_GET["id_carte"])) {
    $idCarte = filter_var($_GET["id_carte"], FILTER_VALIDATE_INT);

    $idTheme = intval($_GET["id_theme"]);

    if($idCarte !== false) {

        $idUser = $_SESSION["user"]["id_utilisateur"];

        $sql = "DELETE carte FROM carte JOIN theme ON carte.id_theme = theme.id_theme WHERE id_carte = :id_carte AND id_utilisateur = :id_utilisateur";

        $requete = $db->prepare($sql);
        $requete -> bindParam(":id_carte", $idCarte, PDO::PARAM_INT);
        $requete -> bindParam(":id_utilisateur", $idUser, PDO::PARAM_INT);
        $requete-> execute();
        header("location:/PAGE/cartes.php?id_theme=" . $idTheme);
    }
} else{
    header("location:/PAGE/cartes.php?id_theme=" . $idTheme);
}
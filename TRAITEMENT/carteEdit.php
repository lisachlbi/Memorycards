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

    if (empty($_POST["questionRecto"])) {
        $empty = true;
        $_SESSION["error"]["questionRecto"] = "<div style='color: black;'class='error'>La question est requise</div>";
        header("location:/PAGE/cartes.php?id_carte=" . $_GET["id_carte"]);
    };

    if (empty($_POST["questionVerso"])) {
        $empty = true;
        $_SESSION["error"]["questionVerso"] = "<div style='color: black;'class='error'>La réponse est requise</div>";
        header("location:/PAGE/cartes.php?id_carte=" . $_GET["id_carte"]);
    };


    if (!empty($_SESSION["error"])) {
        // s'il y a des erreurs, on veut garder ce que l'utilisateur a saisie
        $_SESSION['saisie'] = $_POST;
    }

   

    if (!empty($_POST["questionRecto"]) && !empty($_POST["questionVerso"])) {

        $user = $_SESSION["user"]["id_utilisateur"]; 

        $idTheme = intval($_GET["id_theme"]);
        $idCarte = intval($_GET["id_carte"]);

        $questionRecto = strip_tags($_POST["questionRecto"], FILTER_SANITIZE_SPECIAL_CHARS);

        $questionVerso = strip_tags($_POST["questionVerso"], FILTER_SANITIZE_SPECIAL_CHARS);

        $dateCreation = date('Y-m-d');


        $sql = "UPDATE carte SET recto = :recto, verso = :verso,  date_modification = :date_modification WHERE id_carte = :id_carte";

        $requete = $db->prepare($sql);
        $requete->bindParam(':recto', $questionRecto);
        $requete->bindParam(':verso', $questionVerso);
        $requete->bindParam(':date_modification', $dateCreation);
        $requete->bindParam(':id_carte', $idCarte);

        $requete->execute();

        $lastId = $db->lastInsertId();
    

        if ($_FILES["imgRecto"]["size"] != 0) {
            // On récupère nom de l'image
            $nomImgRecto = $_FILES["imgRecto"]["name"];
            // On donne un nom temporaire
            $nomTemporaire = $_FILES["imgRecto"]["tmp_name"];
            // On récupère l'heure
            $time = time();
            //on renome l'image (heure + nom = unique)
            $nouveauNom = $time . $nomImgRecto;
            // on déplace l'image
            $chemin = "/IMAGE_BDD/" . $nouveauNom;

            move_uploaded_file($nomTemporaire, "../IMAGE_BDD/" . $nouveauNom);

            $sql = "UPDATE carte SET img_recto = :img_recto WHERE id_carte = :id_carte ";
            $requete = $db->prepare($sql);
            $requete->bindParam(':img_recto', $chemin);
            $requete->bindParam(':id_carte', $idCarte);
            $requete->execute();
        }

        if ($_FILES["imgVerso"]["size"] != 0) {
            $nomImgVerso = $_FILES["imgVerso"]["name"];
            $nomTemporaireSeconde = $_FILES["imgVerso"]["tmp_name"];
            $nouveauNomSeconde = $time . $nomImgVerso;

            $destination = "/IMAGE_BDD/" . $nouveauNomSeconde;

            move_uploaded_file($nomTemporaireSeconde, "../IMAGE_BDD/" . $nouveauNomSeconde);

            $sql = "UPDATE carte SET img_verso = :img_verso WHERE id_carte = :id_carte ";
            $requete = $db->prepare($sql);
            $requete->bindParam(':img_verso', $destination);
            $requete->bindParam(':id_carte', $idCarte);
            $requete->execute();
        }

        header("location: /PAGE/cartes.php?id_theme=" . $idTheme);
    }
} else {
    header("location: /PAGE/cartes.php?id_theme=" . $idTheme);
}

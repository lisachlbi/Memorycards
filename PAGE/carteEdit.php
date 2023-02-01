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
    <title>Édition de mes cartes</title>
</head>

<body>

    <?php
    session_start(["user"]);
    if (empty($_SESSION)) {
        header("location:/PAGE/connexion.php");
    };
    include_once('nav.php');
    require_once '../CONFIG/config.php';
    require_once '../CONFIG/PDO.php';
    ?>

    <?php
    $idUser = $_SESSION["user"]["id_utilisateur"];
    // Passe par l'URL
    $idCarte = $_GET["id_carte"];
    ?>
    <?php


    if (!empty($_GET["id_carte"])) {

        $idCarte = intval($_GET["id_carte"]);

        if (filter_var($idCarte, FILTER_VALIDATE_INT) !== false) {

            //on fait la requête SQL qui permet de récupérer la nom du thème 
            $sqlCarte = "SELECT * FROM carte WHERE carte.id_carte = :id_carte";
            $req = $db->prepare($sqlCarte);
            $req->bindParam(":id_carte", $idCarte, PDO::PARAM_INT);
            $req->execute();

            $carte = $req->fetch(PDO::FETCH_ASSOC);
        } else {

            header("location:/PAGE/mesThemes.php");
        }
    } else {
        header("location:/PAGE/mesThemes.php");
    }
    ?>
    <main>
        <h1> Modifier la carte <?php echo $carte['recto']; ?></h1>
        <section>

            <div class="affichage">
                <div class="show-affichage">

                    <form enctype="multipart/form-data" id="gridColumns" method="post" action="/TRAITEMENT/carteEdit.php?id_carte=<?php echo $carte["id_carte"]?>&id_theme=<?php echo $_GET["id_theme"]?>">

                        <input type="text" placeholder="Votre question ?" name="questionRecto" id="titre" value="<?php echo $carte["recto"] ?>">
                        <?php
                        if (!empty($_SESSION["error"]["questionRecto"])) {
                            echo $_SESSION["error"]["questionRecto"];
                            unset($_SESSION["error"]["questionRecto"]);
                        }
                        ?>

                        <div class="ajoutImg">
                            <label for="imgRecto">Image Recto : </label>
                            <input style="border-radius: 0px;" type="file" name="imgRecto" id="imgRecto">
                        </div>

                        <input type="text" placeholder="Votre question ?" name="questionVerso" id="titre" value="<?php echo $carte["verso"] ?>">
                        <?php
                        if (!empty($_SESSION["error"]["questionVerso"])) {
                            echo $_SESSION["error"]["questionVerso"];
                            unset($_SESSION["error"]["questionVerso"]);
                        }
                        ?>

                        <div class="ajoutImg">
                            <label for="imgVerso">Image Verso : </label>
                            <input style="border-radius: 0px;" type="file" name="imgVerso" id="imgVerso">
                        </div>

                        <div class="ajouter">
                            <input required type="submit" name="ajouter" id="ajouter" value="Mettre à jour">
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </main>

</body>
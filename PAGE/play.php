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
    <script src="play.js" defer></script>
    <title>Let's Play</title>
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
    if (!empty($_GET["id_theme"])) {

        $idTheme = intval($_GET["id_theme"]);

        $idUser = $_SESSION["user"]["id_utilisateur"];

        if (filter_var($idTheme, FILTER_VALIDATE_INT) !== false) {

            $sqlTheme = "SELECT nom_theme, id_utilisateur FROM theme WHERE id_theme = :id_theme";
            $req = $db->prepare($sqlTheme);
            $req->bindParam(":id_theme", $idTheme, PDO::PARAM_INT);
            $req->execute();

            $theme = $req->fetch(PDO::FETCH_ASSOC);
        } else {

            header("location:/PAGE/mesThemes.php");
        }
    } else {
        header("location:/PAGE/mesThemes.php");
    }

    if (isset($_POST["ajouter"])) {

        unset($_SESSION["error"]);
        $_SESSION["error"] = [];

        $empty = false;

        //* On stocke en Session un message d'erreur si la saisie est vide
        if (empty($_POST["nbr-carte"])) {
            $empty = true;
            $_SESSION["error"]["nbr-carte"] = "<div style='color: black;'class='error'>Le nombre de cartes est requis</div>";
            header("location:/PAGE/paramPlay.php?id_theme=$idTheme");
            exit();
        }

        $nbrCartes = filter_var($_POST["nbr-carte"], FILTER_SANITIZE_NUMBER_INT);

        $sql = "SELECT * FROM carte WHERE id_theme = :id_theme ORDER BY RAND() LIMIT :nbrCarte";

        $requete = $db->prepare($sql);
        $requete->bindParam(":id_theme", $idTheme, PDO::PARAM_INT);
        $requete->bindParam(":nbrCarte", $nbrCartes, PDO::PARAM_INT);
        $requete->execute();

        $cartes = $requete->fetchAll();
    }
    ?>

    <main>

        <h1>Partie <?php echo $theme["nom_theme"]; ?> </h1>

        <section>
            <div class="affichage">
                <div class="show-carte">
                    <div class="centered-carte">

                        <?php

                        //* Création d'une boucle for each pour recupérer et afficher chaque élément
                        foreach ($cartes as $carte) : ?>

                            <div class="play-carte">
                                <div class="flip-carte">
                                    
                                    <div class="dos-carte carte-recto">
                                        <div class="style-titre">
                                            <h2><?php echo $carte['recto'] ?></h2>
                                        </div>
                                        <?php if ($carte["img_recto"]) : ?>
                                            <div class="containerImg">
                                                <img class="img-carte" src="<?php echo $carte["img_recto"] ?>" alt="">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="dos-carte carte-verso">
                                        <div class="style-titre">
                                            <h2><?php echo $carte['verso'] ?></h2>
                                        </div>
                                        <?php if ($carte["img_verso"]) : ?>
                                            <div class="containerImg">
                                                <img class="img-carte" src="<?php echo $carte["img_verso"] ?>" alt="">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach ?>
                    </div>
                </div>
                <div class="button-play">
                    <div class="play">
                        <a href="/PAGE/accueil.php">J'ai compris !</a>
                    </div>
                </div>
                
            </div>

        </section>
    </main>

</body>

</html>
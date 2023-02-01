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
    <script src="main.js" defer></script>
    <title>Me cartes</title>
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

            $sql = "SELECT * FROM carte JOIN theme ON carte.id_theme = theme.id_theme WHERE carte.id_theme  = :id_theme";

            $requete = $db->prepare($sql);
            $requete->bindParam(":id_theme", $idTheme, PDO::PARAM_INT);
            $requete->execute();

            $cartes = $requete->fetchAll(PDO::FETCH_ASSOC);
        } else {

            header("location:/PAGE/mesThemes.php");
        }
    } else {
        header("location:/PAGE/mesThemes.php");
    }
    ?>

    <main>
        <h1>Les cartes de <?php echo $theme["nom_theme"]; ?></h1>
        <section>
            <div class="affichage">
                <div class="button-play">
                    <div class="play">
                        <a href="/PAGE/paramPlay.php?id_theme=<?php echo $idTheme ?>">Lancer la partie</a>
                    </div>
                </div>

                <div id="grid" class="show-affichage">
                    <?php

                    //* A l'aide de la boucle for each on vient afficher chaque élément 
                    foreach ($cartes as $carte) : ?>

                        <div class="show-grid">
                            <!--On affiche le nom des theme -->
                            <h2><?php echo $carte['recto'] ?></h2>


                            <?php if ($carte["img_recto"]) : ?>
                                <div class="containerImg">
                                    <img class="img" src="<?php echo $carte["img_recto"] ?>" alt="">
                                </div>
                            <?php endif; ?>

                            <?php if ($carte["id_utilisateur"] == $idUser) : ?>
                                <div class="show-outils">
                                    <a href="/PAGE/carteEdit.php?id_carte=<?php echo $carte['id_carte'] ?>&id_theme=<?php echo $idTheme?>">
                                        <span class="material-symbols-outlined edit-themes edit">drive_file_rename_outline</span>
                                    </a>

                                    <span class="material-symbols-outlined edit-moins carte supp" data-id-carte="<?php echo $carte["id_carte"];?>" data-id-theme="<?php echo $idTheme;?>">do_not_disturb_on</span>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($theme["id_utilisateur"] == $idUser) : ?>
                    <span id="plus" class="material-symbols-outlined">add_circle</span>
                <?php endif; ?>
            </div>

            <div class="modal-wrapper">
                <div class="affichage-popup hide-popup">
                    <form id="gridColumns" enctype="multipart/form-data" action="/TRAITEMENT/ajoutCarte.php?id_theme=<?php echo $_GET["id_theme"] ?>" method="post">

                        <input type="text" placeholder="Votre question" name="questionRecto" value="<?php if (isset($_SESSION["saisie"])) {
                                                                                                        echo $_SESSION["saisie"]["questionRecto"];
                                                                                                    } ?>">
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

                        <input type="text" placeholder="Votre reponse" name="questionVerso" value="<?php if (isset($_SESSION["saisie"])) {
                                                                                                        echo $_SESSION["saisie"]["questionVerso"];
                                                                                                    } ?>">
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
                            <input type="submit" name="ajouter" id="ajouter" value="Ajouter">
                        </div>
                    </form>
                </div>
            </div>
        </section>

    </main>

</body>
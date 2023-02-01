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
    <title>Accueil</title>
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

    <main>

        <h1>Derniers thèmes créés</h1>

        <section>
            <div class="affichage">
                <div id="grid" class="show-affichage">
                    <?php

                    $idUser = $_SESSION["user"]["id_utilisateur"];


                    $sql = "SELECT * FROM theme WHERE id_utilisateur = :id_utilisateur ORDER BY date_creation DESC";
                    $requete = $db->prepare($sql);
                    $requete->bindParam(":id_utilisateur", $idUser, PDO::PARAM_INT);
                    $requete->execute();


                    $themes = $requete->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($themes as $theme) :
                    ?>

                        <div class="show-grid">

                            <div class="titre-grid">
                                <a href="/PAGE/mesCartes.php?id_theme=<?php echo $theme['id_theme'] ?>">
                                    <h2><?php echo $theme['nom_theme'] ?></h2>
                                </a>

                                <h3><?php echo $theme['description'] ?></h3>
                            </div>

                            <div class="show-outil">
                                <span id="date-creation"> <?php echo $theme['date_creation'] ?></span>
                            </div>

                        </div>

                    <?php endforeach ?>

                </div>
            </div>
        </section>
    </main>

</body>

</html>
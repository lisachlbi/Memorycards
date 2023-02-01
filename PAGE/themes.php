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
    <title>Les thèmes</title>
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

        <h1>Les thèmes</h1>

        <section>

            <div class="affichage">

                <div id="grid" class="show-affichage">

                    <?php

                    $idUser = $_SESSION["user"]["id_utilisateur"];
                    //* On recupere tous les themes depuis la base de données

                    $sql = "SELECT * FROM theme";

                    $requete = $db->prepare($sql);
                    $requete->execute();

                    $themes = $requete->fetchAll(PDO::FETCH_ASSOC);

                    $filteredThemes = array_filter($themes, function ($elem) {
                        if ($elem['public'] == "1" || ($elem['public'] == "0" && $elem['id_utilisateur'] == $_SESSION['user']['id_utilisateur'])) {
                            return $elem;
                        }
                    });


                    //* A l'aide de la boucle for each on vient afficher chaque élément 
                    foreach ($filteredThemes as $theme) : ?>

                        <div class="show-grid">
                            <!--On affiche le nom des theme -->
                            <div class="titre-grid">
                                <a href="/PAGE/cartes.php?id_theme=<?php echo $theme['id_theme'] ?>">
                                    <h2><?php echo $theme['nom_theme'] ?></h2>
                                </a>
                            </div>
                            <?php

                            //* Si le thème appartient à l'utilisateur celui-ci peut l'éditer et/ou le supprimer
                            if ($idUser == $theme["id_utilisateur"]) : ?>

                                <div class="show-outils">
                                    <a href="/PAGE/themeEdit.php?id_theme=<?php echo $theme['id_theme'] ?>">
                                        <span class="material-symbols-outlined edit-themes edit">drive_file_rename_outline</span>
                                    </a>

                                    <span class="material-symbols-outlined edit-moins  theme supp" data-id="<?php echo $theme['id_theme']; ?>">do_not_disturb_on</span>
                                </div>
                            <?php endif;

                            ?>
                        </div>
                    <?php endforeach ?>


                </div>
                <span id="plus" class="material-symbols-outlined">add_circle</span>
            </div>

            <!-- Création d'une modale pour que l'utilisateur ajoute des thèmes-->
            <div class="modal-wrapper">
                <div class="affichage-popup hide-popup">
                    <form id="gridColumns" action="/TRAITEMENT/ajoutTheme.php" method="post">
                        <input type="text" placeholder="Titre" name="titre" id="titre" value="<?php if (isset($_SESSION["saisie"]["titre"])) {
                                                                                                    echo $_SESSION["saisie"]["titre"];
                                                                                                } ?>">
                        <?php
                        if (!empty($_SESSION["error"]["titre"])) {
                            echo $_SESSION["error"]["titre"];
                            unset($_SESSION["error"]["titre"]);
                        }
                        ?>
                        <input type="textarea" placeholder="Description..." name="description" id="description" value="<?php if (isset($_SESSION["saisie"]["description"])) {
                                                                                                                            echo $_SESSION["saisie"]["description"];
                                                                                                                        } ?>">
                        <?php
                        if (!empty($_SESSION["error"]["description"])) {
                            echo $_SESSION["error"]["description"];
                            unset($_SESSION["error"]["description"]);
                        }
                        ?>

                        <select name="themes_choose" id="themes-choose">
                            <option value="privé">Thèmes privés</option>
                            <option value="public">Thèmes publics</option>
                        </select>
                        <?php
                        if (isset($_SESSION["error"]["themes_choose"])) {
                            echo $_SESSION["error"]["themes_choose"];
                            unset($_SESSION["error"]["themes_choose"]);
                        }
                        ?>

                        <select name="select_categorie" id="select-categorie">

                            <?php

                            $sql = "SELECT * FROM categorie";

                            $requete = $db->prepare($sql);
                            $requete->execute();

                            $categories = $requete->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($categories as $categorie) : ?>

                                <option value="<?php echo $categorie['id_categorie'] ?>"><?php echo $categorie['nom'] ?></option>

                            <?php endforeach ?>

                        </select>

                        <div class="ajouter">
                            <input required type="submit" name="ajouter" id="ajouter" value="Ajouter">
                            <?php if (!empty($_SESSION["error"]["duplicateTitre"])) {
                                echo $_SESSION["error"]["duplicateTitre"];
                                unset($_SESSION["error"]["duplicateTitre"]);
                            } ?>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>


</body>

</html>
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
    <title>Thèmes par catégorie</title>
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

    // On test qu'il y a bien un id_categorie en paramètre, que c'est bien un entier, 
    if (isset($_GET["id_categorie"])) {

        unset($_SESSION["error"]);
        $_SESSION["error"] = [];

        $empty = false;

        $idCategorie = intval($_GET["id_categorie"]);

        if (filter_var($idCategorie, FILTER_VALIDATE_INT) !== false) {

            //on fait la requête SQL qui permet de récupérer la nom de la catégorie 
            $sqlCategorie = "SELECT nom FROM categorie WHERE categorie.id_categorie = :id_categorie";
            $req = $db->prepare($sqlCategorie);
            $req->bindParam(":id_categorie", $idCategorie, PDO::PARAM_INT);
            $req->execute();

            $categorieName = $req->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT * FROM theme WHERE theme.id_categorie = :id_categorie";
            $requete = $db->prepare($sql);
            $requete->bindParam(":id_categorie", $idCategorie, PDO::PARAM_INT);
            $requete->execute();

            $theme = $requete->fetchAll(PDO::FETCH_ASSOC);

            // On filtre les thèmes pour que les thèmes privés créés par d'autres utilisateurs n'apparaissent pas
            $filteredThemes = array_filter($theme, function ($elem) {
                if ($elem['public'] == "1" || ($elem['public'] == "0" && $elem['id_utilisateur'] == $_SESSION['user']['id_utilisateur'])) {
                    return $elem;
                }
            });
        } else {

            header("location:/PAGE/categories.php");
            // Si pas d'id_cat donné en paramètre, on redirige vers la page des catégorie 
        }
    }

    ?>
    <main>
        <h1>Les thèmes de <?php echo $categorieName['nom']; ?></h1>
        <section>
            <div class="affichage">

                <div id="grid" class="show-affichage">

                    <?php

                    $idUser = $_SESSION["user"]["id_utilisateur"];

                    $public = $_POST['themes_choose'] === "public" ? 1 : 0;


                    //* On affiche les thèmes publics et privés créés par l'utilisateur 
                    if (filter_var($idCategorie, FILTER_VALIDATE_INT)) {

                        $sql = "SELECT * FROM theme JOIN utilisateur ON theme.id_utilisateur = utilisateur.id_utilisateur WHERE theme.id_utilisateur  = :id AND theme.id_categorie = :id_categorie AND public = :public";

                        $requete = $db->prepare($sql);
                        $requete->bindParam(":id", $idUser, PDO::PARAM_INT);
                        $requete->bindParam(":id_categorie", $idCategorie, PDO::PARAM_INT);
                        $requete->bindParam(":public", $public, PDO::PARAM_BOOL);

                        $requete->execute();

                        $themes = $requete->fetchAll(PDO::FETCH_ASSOC);

                        //* La boucle for each nous permet d'afficher chaque élément on passe en parametre filteredthemes par ne pas afficher des themes privés créés par d'autre utilisateur
                        foreach ($filteredThemes as $theme) : ?>

                            <div class="show-grid">
                                <a href="/PAGE/cartes.php?id_theme=<?php echo $theme['id_theme'] ?>">
                                    <h2><?php echo $theme['nom_theme'] ?></h2>
                                </a>

                                <!--Peut modifier ou supprimer que des themes qui lui appartiennent -->
                                <?php if ($idUser == $theme["id_utilisateur"]) : ?>

                                    <div class="show-outils">
                                        <a href="/PAGE/themeEdit.php?id_theme=<?php echo $theme['id_theme'] ?>">
                                            <span class="material-symbols-outlined edit-themes edit">drive_file_rename_outline</span>
                                        </a>

                                        <span class="material-symbols-outlined edit-moins  theme supp" data-id="<?php echo $theme['id_theme']; ?>">do_not_disturb_on</span>
                                    </div>
                                <?php endif ?>
                            </div>

                    <?php endforeach;
                    } ?>


                </div>
                <span id="plus" class="material-symbols-outlined">add_circle</span>
            </div>

            <div class="modal-wrapper">
                <div class="affichage-popup hide-popup">
                    <form id="gridColumns" action="/TRAITEMENT/ajoutTheme.php?id_categorie=<?php echo $_GET["id_categorie"] ?>" method="post">
                        <input required type="text" placeholder="Titre" name="titre" id="titre" 
                        value="<?php if (isset($_SESSION["saisie"])) {echo $_SESSION["saisie"]["titre"];} ?>">
                        <?php
                        if (!empty($_SESSION["error"]["titre"])) {
                            echo $_SESSION["error"]["titre"];
                            unset($_SESSION["error"]["titre"]);
                        }
                        ?>
                        <input required type="textarea" name="description" placeholder="Description..." id="description"
                        value="<?php if (isset($_SESSION["saisie"])) {echo $_SESSION["saisie"]["description"];} ?>">
                        <?php
                        if (!empty($_SESSION["error"]["description"])) {
                            echo $_SESSION["error"]["description"];
                            unset($_SESSION["error"]["description"]);
                        }
                        ?>

                        <select required name="themes_choose" id="themes-choose">
                            <option value="privé">Thèmes privés</option>
                            <option value="public">Thèmes publics</option>
                        </select>

                        <div class="ajouter">
                            <input type="submit" name="ajouter" id="ajouter" value="Ajouter">
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
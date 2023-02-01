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
    <title>Édition de mes thèmes</title>
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
    $idTheme = $_GET["id_theme"];
    ?>

    <?php


    if (!empty($_GET["id_theme"])) {

        $idTheme = intval($_GET["id_theme"]);

        if (filter_var($idTheme, FILTER_VALIDATE_INT) !== false) {

            //on fait la requête SQL qui permet de récupérer la nom du thème 
            $sqlTheme = "SELECT * FROM theme WHERE theme.id_theme = :id_theme";
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
    ?>

    <main>
        <h1> Modifier le thème <?php echo $theme['nom_theme']; ?></h1>
        <section>

            <div class="affichage">
                <div class="show-affichage">

                    <form id="gridColumns" method="post" action="/TRAITEMENT/themeEdit.php?id_theme=<?php echo $theme["id_theme"] ?>">

                        <input type="text" placeholder="" name="titre" id="titre" value="<?php echo $theme["nom_theme"]?>">
                        <?php
                        if (!empty($_SESSION["error"]["titre"])) {
                            echo $_SESSION["error"]["titre"];
                            unset($_SESSION["error"]["titre"]);
                        }
                        ?>
                        <input type="textarea" name="description" placeholder="" id="description" value="<?php echo $theme["description"]?>">
                        <?php
                        if (!empty($_SESSION["error"]["description"])) {
                            echo $_SESSION["error"]["description"];
                            unset($_SESSION["error"]["description"]);
                        }
                        ?>

                        <select name="themes-choose" id="themes-choose">
                            <option value="prives">Thèmes privés</option>
                            <option value="publics">Thèmes publics</option>
                        </select>

                        <div class="ajouter">
                            <input required type="submit" name="ajouter" id="ajouter" value="Mettre à jour">
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </main>

</body>

</html>
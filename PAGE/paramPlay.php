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
    <title>Paramètre</title>
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

        <?php
        if (empty($_GET["id_theme"])) {
            header("location:/PAGE/mesThemes.php");
            exit;
        }
        $idTheme = intval($_GET["id_theme"]);

        $sql = "SELECT COUNT(id_carte) FROM carte WHERE id_theme = :id_theme";

        $requete = $db->prepare($sql);
        $requete->bindParam(":id_theme", $idTheme, PDO::PARAM_INT);
        $requete->execute();

        $countCarte = $requete->fetch(PDO::FETCH_NUM);
        ?>

        <h1>Selectionner le nombre de carte</h1>

        <section>
            <div class="affichage">
                <div class="show-affichage">
                    <div class="show-param">

                        <form class="form-categorie" action="/PAGE/play.php?id_theme=<?php echo $idTheme ?>" method="post">

                            <div class="input-icon">
                                <input type="number" min="1" max="<?php echo $countCarte[0] ?>" placeholder="Nombre de carte" name="nbr-carte" id="nbr-carte" value="<?php if (isset($_SESSION["saisie"])) echo $_SESSION["saisie"]["nbr-carte"] ?>">
                                <span id="play" class="material-symbols-outlined">play_circle</span>
                            </div>
                            <?php
                            if (!empty($_SESSION["error"]["nbr-carte"])) {
                                echo $_SESSION["error"]["nbr-carte"];
                                unset($_SESSION["error"]["nbr-carte"]);
                            }
                            ?>
                            <div class="ajouter">
                                <input required type="submit" name="ajouter" id="ajouter" value="Let's play">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>

</html>
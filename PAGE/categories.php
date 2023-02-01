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
    <title>Les catégories</title>
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
        <h1>Les catégories</h1>
        <section>

            <div class="affichage">
                <div class="show-affichage">

                    <?php

                    //* On recupère les éléments de la table catgeorie

                    $sql = "SELECT * FROM categorie";

                    $requete = $db->prepare($sql);
                    $requete->execute();

                    $categories = $requete->fetchAll(PDO::FETCH_ASSOC);

                    //* Création d'une boucle for each pour recupérer et afficher chaque élément
                    foreach ($categories as $categorie) : ?>

                        <div class="show-categorie">
                            <a href="/PAGE/themesParCategorie.php?id_categorie=<?php echo $categorie['id_categorie'] ?>">
                                <h2><?php echo $categorie['nom'] ?></h2>
                            </a>
                        </div>
                    <?php endforeach ?>

                </div>
                <span id="plus" class="material-symbols-outlined">add_circle</span>
            </div>

            <!-- Création d'une modale pour que l'utilisateur puisse créer une catégorie -->
            <div class="modal-wrapper">

                <div class="affichage-popup hide-popup">
                    <form class="form-categorie" action="/TRAITEMENT/ajoutCategorie.php" method="post">

                        <!-- On stocke le nom de la catégorie-->
                        <div class="input-icon">
                            <input type="text" placeholder="Nom" name="nom-categorie" id="nom-categorie" 
                            value="<?php if (isset($_SESSION["saisie"])) echo $_SESSION["saisie"]["nom-categorie"] ?>">
                            <span id="modifier" class="material-symbols-outlined plus-categorie">edit_square</span>
                        </div>
                        <?php
                        if (!empty($_SESSION["error"]["nom-categorie"])) {
                            echo $_SESSION["error"]["nom-categorie"];
                            unset($_SESSION["error"]["nom-categorie"]);
                        }
                        ?>

                        <!-- On interdit la duplication d'un même nom de catégorie-->
                        <div class="ajouter">
                            <input required type="submit" name="ajouter" id="ajouter" value="Ajouter">
                            <?php
                            if (!empty($_SESSION["error"]["duplicateNom"])) {
                                echo $_SESSION["error"]["duplicateNom"];
                                unset($_SESSION["error"]["duplicateNom"]);
                            }

                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
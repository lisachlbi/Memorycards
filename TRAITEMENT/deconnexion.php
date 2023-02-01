<?php
session_start();
//Supprimer la session USER
session_destroy();
session_unset();

header("Location:/PAGE/connexion.php");
?>
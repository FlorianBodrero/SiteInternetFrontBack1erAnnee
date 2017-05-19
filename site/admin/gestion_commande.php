<?php
require_once("../inc/init.inc.php");
# ----------------- TRAITEMENT PHP
if (!internauteEstConnecteEtEstAdmin()) {
    header("location:../connexion.php");
}

require_once("../inc/haut.inc.php");

$commandes = executeRequete("SELECT * FROM commande");?>

 <h1>Bienvenu sur e-Ynov</h1>

 <?php require_once("../inc/bas.inc.php"); ?>

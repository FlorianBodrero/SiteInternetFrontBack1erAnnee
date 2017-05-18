<?php require_once ("inc/init.inc.php");

# --------------------------- TRAITEMENT PHP

if (!internauteEstConnecte()) {
    header("location:connexion.php");
}

$contenu .= '<p class= "centre"> Bonjour <strong> '.$_SESSION['membre']['pseudo'].'</strong></p>';

$contenu.= '<div class="cadre"> <h2> Voici vos informations perso </h2> </div>';

$contenu .= '<p class= "centre"> Votre email est :  <strong> '.$_SESSION['membre']['email'].'</strong></p>';
$contenu .= '<p class= "centre"> Votre adresse est :  <strong> '.$_SESSION['membre']['adresse'].'</strong></p>';
$contenu .= '<p class= "centre"> ville <strong> '.$_SESSION['membre']['ville'].'</strong></p>';
$contenu .= '<p class= "centre"> Code postal : <strong> '.$_SESSION['membre']['code_postal'].'</strong></p>';
$contenu .= '<p class= "centre"> Bonjour <strong> '.$_SESSION['membre']['pseudo'].'</strong></p>';

require_once ("inc/haut.inc.php");
echo $contenu;
require_once ("inc/bas.inc.php");

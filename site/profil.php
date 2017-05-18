<?php require_once("inc/init.inc.php");

//-----------------------TRAITEMENT PHP
if(!internauteEstConnecte()){
    header("location:connexion.php");
}
$pseudo = $_SESSION['membre']['pseudo'];
$contenu .= '<p class="centre">Bonjour <strong>'.$_SESSION['membre']['pseudo']
    .'</strong></p>';
$contenu .= '<div class="cadre"><h2>Voici vos informations :</h2></div>';
$contenu .= 'Votre email est :'.$_SESSION['membre']['email'].'<br/>';
$contenu .= 'Votre ville est :'.$_SESSION['membre']['ville'].'<br/>';
$contenu .= 'Votre code postal est :'.$_SESSION['membre']['code_postal'].'<br/>';
$contenu .= 'Votre adresse est :'.$_SESSION['membre']['adresse'].'<br/>';
$contenu .= '<form method="post" action=""><input name="desincription" value="Se désinscrire" type="submit"></form>';

if($_POST){
    executeRequete("DELETE FROM membre WHERE pseudo = '$pseudo'");

    header("location:inscription.php");
    echo "vous avez bien été désinscrit";
}

require_once("inc/haut.inc.php");
echo $contenu;
require_once("inc/bas.inc.php");

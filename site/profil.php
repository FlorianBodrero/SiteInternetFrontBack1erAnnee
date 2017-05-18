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
?>




<!--    EXO 5 - AJOUT PHOTO POUR AVATAR-->
<div style="text-align: right">
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
        <h3> Ajout image avatar pour votre profil</h3>
        <input type="file" name="photo" /> <br/>
        <input type="submit" />
    </form>
</div>


<?php
if (isset($_FILES['photo']) AND !empty($_FILES['photo'])) {
    $maxSize = 2200000; // taille max d'une image
    $fileExtension = array("jpg", "png", "jpeg"); // vérif extension d'une image

    if ($_FILES['photo']['size'] <= $maxSize) {
        $extensionUpload = strtolower(substr(strrchr($_FILES['photo']['name'], '.'), 1)); // strrchr : renvoie l'extension du fichier avec le . le subtring enlève le . tout mettre en minuscule
        if (in_array($extensionUpload, $fileExtension)) { // on teste si l'extension est bonne
            $chemin = "inc/img/photoAvatar/";
            var_dump($chemin);
            var_dump($_FILES['photo']);
            var_dump($extensionUpload);
            $resultat = move_uploaded_file($_FILES['photo']['tmp_name'], $chemin.$_SESSION['membre']['id_membre'].".".$extensionUpload );
            var_dump($resultat);
            if ($resultat) {
                executeRequete("INSERT INTO imageavatar (image) VALUE ('".$_SESSION['membre']['id_membre'].".".$extensionUpload."')");
            } else {
                echo 'il y a eu une erreur durant l\'importation de votre photo de profil. Voici l\'erreur : '. $mysqli -> error;
            }

        } else {
            echo 'Votre photo d\'avater doit etre au bon format : jpg, jpeg, ou png';
        }
    
    } else {
        echo 'Votre image doit avoir une taille inférieur à 2Mo';
    }

}




require_once("inc/bas.inc.php");

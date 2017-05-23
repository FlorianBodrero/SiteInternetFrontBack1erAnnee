<?php require_once("inc/init.inc.php");

//-----------------------TRAITEMENT PHP
if(!internauteEstConnecte()){
	header("location:connexion.php");
}

$currentId = $_SESSION['membre']['id_membre'];

$currentPseudo = $_SESSION['membre']['pseudo'];
$recupMdp = executeRequete("SELECT mdp FROM membre WHERE pseudo = '$currentPseudo'");
while ($ligne = $recupMdp->fetch_assoc()) {
    foreach ($ligne as $indice => $information) {
    }
}
$information = decrypt($information, $_SESSION['membre']['pseudo']);

if($_POST)
{
	$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']);
	if(!$verif_caractere || strlen($_POST['pseudo']) < 1 || strlen($_POST['pseudo']) > 20 )
	{
		$contenu .= "<div class='erreur'>Le pseudo doit contenir entre 1 et 20 caractères. <br> Caractère accepté : Lettre de A à Z et chiffre de 0 à 9</div>";
	}
    $membre = executeRequete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
    if ($membre->num_rows > 0 && $membre->fetch_object()->pseudo != $_SESSION['membre']['pseudo']) {
        $contenu = "<div class='erreur'> Le pseudo existe déjà. Veuillez le changer </div>";
    }

	elseif(empty($contenu))
	{
			foreach($_POST as $indice => $valeur)
			{
				$_POST[$indice] = htmlEntities(addSlashes($valeur));
                $_SESSION['membre'][$indice] = $_POST[$indice];
			}
			executeRequete("UPDATE membre SET pseudo = '$_POST[pseudo]', mdp = '$_POST[mdp]', nom = '$_POST[nom]', prenom = '$_POST[prenom]', email = '$_POST[email]', civilite  ='$_POST[civilite]', ville = '$_POST[ville]', code_postal = '$_POST[code_postal]', adresse = '$_POST[adresse]' WHERE id_membre = $currentId");
			$contenu .= "<div class='validation'>Vos données ont bien été modifiées. <a href=\"profil.php\"></a></div>";
	}
}



$contenu .= '<form method="post" action="">
    <label for="pseudo">Pseudo</label><br>
    <input type="text" id="pseudo" name="pseudo" maxlength="20" value="'.$_SESSION['membre']['pseudo'].'" required><br><br>

    <label for="mdp">Mot de passe</label><br>
    <input type="text" id="mdp" name="mdp" value="'.$information.'" required><br><br>

    <label for="nom">Nom</label><br>
    <input type="text" id="nom" name="nom" value="'.$_SESSION['membre']['nom'].'"><br><br>

    <label for="prenom">Prénom</label><br>
    <input type="text" id="prenom" name="prenom" value="'.$_SESSION['membre']['prenom'].'"><br><br>

    <label for="email">Email</label><br>
    <input type="email" id="email" name="email" value="'.$_SESSION['membre']['email'].'"><br><br>

    <label for="civilite">Civilité</label><br>
    <input name="civilite" value="m" checked="" type="radio">Homme
    <input name="civilite" value="f" type="radio">Femme<br><br>

    <label for="ville">Ville</label><br>
    <input type="text" id="ville" name="ville" value="'.$_SESSION['membre']['ville'].'"><br><br>

    <label for="cp">Code Postal</label><br>
    <input type="text" id="code_postal" name="code_postal" value="'.$_SESSION['membre']['code_postal'].'"><br><br>

    <label for="adresse">Adresse</label><br>
    <textarea id="adresse" name="adresse">'.$_SESSION['membre']['adresse'].'</textarea><br><br>

    <input name="modifier" value="Modifier" type="submit">
	<input name"annuler" value="Annuler" type="reset">';

require_once("inc/haut.inc.php");
echo $contenu;
require_once("inc/bas.inc.php");

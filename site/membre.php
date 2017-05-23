<?php require_once("inc/init.inc.php");

//-----------------------TRAITEMENT PHP

// pour récupérer les informations de l'utilisateur en cours, on utilise le "$_SESSION" et pour accéder à ses informations on ajoute la table "membre" puis l'information voulu
// Comme par exemple "$_SESSION['membre']['id_membre']" : on récupère l'id_membre de l'utilisateur dans la table membre


// on récupère l'id du membre actuel
$currentId = $_SESSION['membre']['id_membre'];

// petite fonction permettant de récupérer le mot de passe de l'utilisateur
// on récupère le pseudo du membre actuel
$currentPseudo = $_SESSION['membre']['pseudo'];
// grâce à son pseudo on récupère son mot de passe
$recupMdp = executeRequete("SELECT mdp FROM membre WHERE pseudo = '$currentPseudo'");
while ($ligne = $recupMdp->fetch_assoc()) {
    foreach ($ligne as $indice => $information) {
    }
}
// les mots de passe de la base de données son cryptés. Pour qu'il s'affiche chez l'utilisateur on va donc décrypter le mot de passe que l'on récupère
$information = decrypt($information, $_SESSION['membre']['pseudo']);

// on vérifie les conditions du pseudo
if($_POST)
{
    // on vérifie que le pseudo ne comporte que des lettres minuscules ou majuscules, des chiffres ou les caractères ".", "_", "-"
	$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']);
	// si les caractères entrés ne sont pas autorisés ou le nombre de caractères dépasse 20, on affiche un message d'erreur
	if(!$verif_caractere || strlen($_POST['pseudo']) < 1 || strlen($_POST['pseudo']) > 20 )
	{
		$contenu .= "<div class='erreur'>Le pseudo doit contenir entre 1 et 20 caractères. <br> Caractère accepté : Lettre de A à Z et chiffre de 0 à 9</div>";
	}
	// on lance une requete de recherche de pseudo dans la bdd
    $membre = executeRequete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
	// si le pseudo entré existe déjà on affiche un message d'erreur
    if ($membre->num_rows > 0 && $membre->fetch_object()->pseudo != $_SESSION['membre']['pseudo']) {
        $contenu = "<div class='erreur'> Le pseudo existe déjà. Veuillez le changer </div>";
    }
    // sinon on modifie les informations dans la bdd
	elseif(empty($contenu))
	{
			foreach($_POST as $indice => $valeur)
			{
				$_POST[$indice] = htmlEntities(addSlashes($valeur));
                $_SESSION['membre'][$indice] = $_POST[$indice];
			}
            $mdp = encrypt($_POST['mdp'], $_POST['pseudo']);
			executeRequete("UPDATE membre SET pseudo = '$_POST[pseudo]', mdp = '$mdp', nom = '$_POST[nom]', prenom = '$_POST[prenom]', email = '$_POST[email]', civilite  ='$_POST[civilite]', ville = '$_POST[ville]', code_postal = '$_POST[code_postal]', adresse = '$_POST[adresse]' WHERE id_membre = $currentId");
			$contenu .= "<div class='validation'>Vos données ont bien été modifiées. <a href=\"profil.php\"></a></div>";
	}
}

// les "value" de chaque input contiennent les informations de l'utilisateur stockées dans la bdd
// le mot de passe n'étant pas retenu et étant crypté on ne peut pas le récupéré comme tel
// Le textarea ne possédant pas de value, on récupère les données directement en tant que texte

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

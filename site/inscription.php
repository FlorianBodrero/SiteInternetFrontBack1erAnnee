<?php require_once("inc/init.inc.php");
# ------------------------------ TRAITEMENT_PHP
if ($_POST) {
    debug($_POST);
    $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST ['pseudo']);
    if (!$verif_caractere && (strlen($_POST['pseudo'] < 1) || strlen($_POST['pseudo']) > 20)) {
        $contenu .= "<div class='erreur'> Le psuedo doit contenir entre 1 et 2 à caractères. Caractères acceptés : Lettre de A à Z, a à z, et chiffre de 0 à 9 </div>";
    } else {
        $membre = executeRequete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
        if ($membre->num_rows > 0) {
            $contenu = "<div class='erreur'> Le psuedo existe déjà. Veuillez le changer </div>";
        } else {
            #on crypte notre mot de passe ici
            #$mdpCrypte = sha1($_POST['mdp']);
            foreach ($_POST as $indice => $valeur) {
                $_POST[$indice] = htmlentities(addslashes($valeur)); #htmlentitites: prend les characteres tel qu'ils sont
            }
            executeRequete("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES ('$_POST[pseudo]', '$_POST[mdp]', '$_POST[nom]','$_POST[prenom]','$_POST[email]','$_POST[civilite]','$_POST[ville]','$_POST[code_postal]','$_POST[adresse]')");
            $contenu .= '<div class="validation"; style="background-color: #669933"> Vous etes inscrit à notre site web <a href="connexion.php"> Cliquez ici pour vous connecter</a></div>';
        }
    }
}


?>
<?php require_once("inc/haut.inc.php"); ?>
<?php echo $contenu ?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="pseudo"> Pseudo </label> <br/>
    <input type="text" id="psuedo" name="pseudo" maxlength="20" placeholder="votre pseudo" required/> <br/>

    <label for="mdp"> Mot de passe </label> <br/>
    <input type="password" id="mdp" name="mdp" maxlength="20" required/> <br/>

    <label for="nom"> Nom </label> <br/>
    <input type="text" id="nom" name="nom" maxlength="20" placeholder="votre Nom" required/> <br/>

    <label for="prenom"> Prenom </label> <br/>
    <input type="text" id="prenom" name="prenom" maxlength="20" placeholder="votre Prénom" required/> <br/>

    <label for="email"> E-mail </label> <br/>
    <input type="email" id="email" name="email" maxlength="50" placeholder="votre E-mail" required/> <br/>

    <label for="civilite"> Civilite </label> <br/>
    <input type="radio" name="civilite" value="m" checked/> Homme
    <input type="radio" name="civilite" value="f"/> Femme <br/>

    <label for="ville"> Ville </label> <br/>
    <input type="text" id="ville" name="ville" maxlength="20" placeholder="votre Ville" required/> <br/>

    <label for="code_postal"> Code Postal </label> <br/>
    <input type="text" id="code_postal" name="code_postal" placeholder="votre pseudo" required/> <br/>

    <label for="adresse"> Adresse </label> <br/>
    <input type="text" id="adresse" name="adresse" maxlength="100" placeholder="votre Adresse" required/> <br/>


    <input type="submit" name="inscription" value="S'inscrire">
</form>


<?php require_once("inc/bas.inc.php"); ?>

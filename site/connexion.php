<?php require_once ("inc/init.inc.php");
# ----------------------------- TRAITEMENTphp

if (isset($_GET['action']) && $_GET['action'] == "deconnexion") {
    session_destroy();
}

if (internauteEstConnecte()) {
    header("location:profil.php");
}



if($_POST) {
    $resultat = executeRequete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]'");
    if ($resultat -> num_rows != 0) {
        $membre = $resultat -> fetch_assoc();
        if ($membre['mdp'] == $_POST ['mdp']) {
            foreach ($membre as $indice => $element) {
                if ($indice != 'mdp') {
                    $_SESSION['membre'][$indice] = $element;
                }
            }
            header("location:profil.php"); #lien vers un autre fichier - redirection
        } else {
            $contenu .= '<div class="erreur"> Erreur de mot de passe </div>';
        }


    } else {
        $contenu .= '<div class="erreur"> Erreur Pseudo </div>';
    }

}





?>
<?php require_once ('inc/haut.inc.php'); ?>
<?php echo $contenu?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="pseudo"> Pseudo </label> <br/>
    <input type="text" id="pseudo" name="pseudo" maxlength="20" placeholder="votre pseudo" required/> <br/>

    <label for="mdp"> Mot de passe </label> <br/>
    <input type="password" id="mdp" name="mdp" maxlength="20" required/> <br/>

    <input type="submit" value="Se connecter" />
</form>


<?php require_once ('inc/bas.inc.php'); ?>

<?php require_once ("inc/init.inc.php");
# ----------------------------- TRAITEMENTphp

if (isset($_GET['action']) && $_GET['action'] == "deconnexion") {
    session_destroy();
}

if (internauteEstConnecte()) {
    header("location:profil.php");
}



if(isset($_POST['connect']) && $_POST['connect']) {
    $resultat = executeRequete("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]' OR email = '$_POST[pseudo]' ");
    if ($resultat -> num_rows != 0) {
        $membre = $resultat -> fetch_assoc();
        if ($membre['mdp'] == decrypt($_POST['mdp'], $membre['pseudo'])) {
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
    <label for="pseudo"> Pseudo / Adresse Email </label> <br/>
    <input type="text" id="pseudo" name="pseudo" maxlength="20" size="30px" placeholder="votre pseudo ou adresse email" required/> <br/>

    <label for="mdp"> Mot de passe </label> <br/>
    <input type="password" id="mdp" name="mdp" maxlength="20" size="30px" placeholder="votre mot de passe" required/> <br/>
    <a href="?mdplost"><u>Mot de passe perdu</u></a><br/><br/>
    <input type="submit" name="connect" value="Se connecter" />
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['mdplost'])) {
    echo '
    <br/>
    <p>Nous allons vous envoyer votre mot de passe par e-mail, pour cela, écrivez votre e-mail :</p>
    <form method="post" action="'.$_SERVER['PHP_SELF'].'">
    <input type="email" id="email" name="email" maxlength="50" placeholder="Votre E mail" required/> <br/>
    <input type="submit" name="send" value="Envoyer" />
    </form>
    ';
}

if(isset($_POST['send']) && $_POST['send']) {
    $query = executeRequete("SELECT email FROM membre WHERE email='".$_POST['email']."'");
    if($query -> num_rows == 1){
        echo '<br/>Nous avons envoyé un message contenant votre mot de passe à l\' adresse : <strong>'.$_POST['email'].
        ' .</strong><br/> Veuillez vérifier vos messages. ';
    }
    else{
    echo '<br/><p>Email inexistant</p>
    <br/>
    <form method="post" action="'.$_SERVER['PHP_SELF'].'">
    <input type="email" id="email" name="email" maxlength="50" placeholder="Votre E mail" required/> <br/>
    <input type="submit" name="send" value="Envoyer" />
    </form>';
    }
}


?>

<?php require_once ('inc/bas.inc.php'); ?>

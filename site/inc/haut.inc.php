<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 09/05/2017
 * Time: 09:18
 */

?>

<!DOCTYPE HTML>
<html>
    <head>
        <title> Mon site d'e-commerce fake</title>
        <meta charset="utf8" />
        <link rel="stylesheet" href="<?php echo RACINE_SITE; ?>inc/css/style.css"/>
    </head>
    <body>
    <header>
        <div class="conteneur">
            <span>
                <a href="#" title="Mon site"> MonsSite.com</a>
            </span>
            <nav>
                <?php
                if (internauteEstConnecteEtEstAdmin()) {
                    echo '<a href="'.RACINE_SITE.'admin/gestion_membre.php"> Gestion des membres </a>';
                    echo '<a href="'.RACINE_SITE.'admin/gestion_commande.php"> Gestion des commandes </a>';
                    echo '<a href="'.RACINE_SITE.'admin/gestion_boutique.php"> Gestion des boutique </a>';
                }
                if (internauteEstConnecte()) {
                    echo '<a href="'.RACINE_SITE.'profil.php"> Mon profil </a>';
                    echo '<a href="'.RACINE_SITE.'boutique.php"> Accès à la boutique </a>';
                    echo '<a href="'.RACINE_SITE.'panier.php"> Mon Panier </a>';
                    echo '<a href="'.RACINE_SITE.'connexion.php?action=deconnexion"> Se déconnecter </a>';
                } else {
                    echo '<a href="'.RACINE_SITE.'inscription.php">Inscription </a>';
                    echo '<a href="'.RACINE_SITE.'connexion.php">Connexion </a>';
                    echo '<a href="'.RACINE_SITE.'boutique.php">Acces à la boutique </a>';
                    echo '<a href="'.RACINE_SITE.'panier.php">Panier </a>';
                }
                ?>
                ?>
            </nav>
        </div>
    </header>
    <section>

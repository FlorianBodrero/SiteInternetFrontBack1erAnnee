<?php
require_once("../inc/init.inc.php");
# ----------------- TRAITEMENT PHP
if (!internauteEstConnecteEtEstAdmin()) {
    header("location:../connexion.php");
}

require_once("../inc/haut.inc.php");

$commandes = executeRequete("SELECT commande.id_commande, commande.date_enregistrement, commande.montant, produit.id_produit, produit.titre, produit.photo, details_commande.quantite, membre.pseudo, membre.adresse, membre.ville, membre.code_postal, commande.etat
    FROM commande, produit, membre, details_commande
    WHERE commande.id_commande = details_commande.id_commande AND produit.id_produit = details_commande.id_produit
    -- GROUP BY commande.id_commande, produit.id_produit, membre.pseudo
    ");

    $contenu .= '<table border="1"><tr>';

    while ($colonne = $commandes->fetch_field()) {
        $contenu .= '<th>' . $colonne->name . ' </th>';
    }
    while ($ligne = $commandes->fetch_assoc()) { # parcours les elements de ma ligne
        $contenu .= '<tr>';
        foreach ($ligne as $item => $value) {
            if ($item == "photo") {
                $contenu .= '<td> <img src="../inc/img/photoProduit/' . $value . '" width = "70px" height = "70px"/></td>';
            } else {
                $contenu .= '<td>' . $value . '</td>';
            }
        }
    }
    $contenu .= '</table>';
    echo $contenu;?>


    <?php require_once("../inc/bas.inc.php"); ?>

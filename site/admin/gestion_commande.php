<?php
require_once("../inc/init.inc.php");
# ----------------- TRAITEMENT PHP
if (!internauteEstConnecteEtEstAdmin()) {
    header("location:../connexion.php");
}

require_once("../inc/haut.inc.php");?>
<?php  ?>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <label for="tri">Trier par :</label>
    <select name="tri">
        <option value="date_enregistrement">Date</option>
        <option value="montant">Prix</option>
        <option value="etat">Etat</option>
    </select>
    <input type="submit" name="trier" value="Trier">
</form>
<?php
if (isset($_POST['tri'])) {
    $commandes = executeRequete("SELECT commande.id_commande, commande.date_enregistrement, commande.montant, membre.pseudo, membre.adresse, membre.ville, membre.code_postal, commande.etat
        FROM commande, membre
        WHERE commande.id_membre = membre.id_membre
        ORDER BY $_POST[tri]
        ");
    }
    else {
        $commandes = executeRequete("SELECT commande.id_commande, commande.date_enregistrement, commande.montant, membre.pseudo, membre.adresse, membre.ville, membre.code_postal, commande.etat
            FROM commande, membre
            WHERE commande.id_membre = membre.id_membre
            ");
        }

        $contenu .= '<table border="1"><tr>';

        while ($colonne = $commandes->fetch_field()) {
            $contenu .= '<th>' . $colonne->name . ' </th>';
        }
        $contenu .= '<th> Détail commande</th>';
        $contenu .= '<th> Modifier état </th>';

        while ($ligne = $commandes->fetch_assoc()) { # parcours les elements de ma ligne
            $id_commande;
            $contenu .= "<form action=\"$_SERVER[PHP_SELF]\" method=\"POST\">";
            $contenu .= '<tr>';
            foreach ($ligne as $item => $value) {
                if ($item == "id_commande") {
                    $id_commande = $value;
                    $contenu .= '<input type="hidden" name="id_commande" value="'. $id_commande .'"/>';
                }

                if ($item == "etat") {
                    $contenu .= '<td>
                    <select name="etat">
                    <option value="en cours de traitement"'; if ($value == "en cours de traitement") $contenu .= "selected"; $contenu .= '>en cours de traitement</option>
                    <option value="envoyé"'; if ($value == "envoyé") $contenu .= "selected"; $contenu .= '>envoyé</option>
                    <option value="livré"'; if ($value == "livré") $contenu .= "selected"; $contenu .= '>livré</option>
                    </select>
                    </td>';
                } else {
                    $contenu .= '<td>' . $value . '</td>';
                }
            }
            $commande = executeRequete("SELECT produit.id_produit, produit.titre, produit.reference, produit.photo, produit.prix, details_commande.quantite
                FROM commande, produit, details_commande
                WHERE commande.id_commande = ". $id_commande ." AND details_commande.id_commande = ". $id_commande ." AND details_commande.id_produit = produit.id_produit
                ");
                $contenu .= "<td>";
                $contenu .= '<table border="1"><tr>';

                while ($colonne = $commande->fetch_field()) {
                    $contenu .= '<th>' . $colonne->name . ' </th>';
                }

                while ($ligne = $commande->fetch_assoc()) { # parcours les elements de ma ligne
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
                $contenu .= '<td><input type="submit" name="changerEtat'.$id_commande.'" value="Valider"/></td>';
                $contenu .= "</tr>";
                $contenu .= "</form>";
            }
            $contenu .= '</table>';
            $chiffre = executeRequete("SELECT SUM(montant) as montant FROM commande");
            $chiffre2 = $chiffre->fetch_object();

            $contenu .= '<p>Chiffre d\'affaire : '.$chiffre2->montant.'€</p>';
            echo $contenu;

            if (isset($_POST['etat'])) {
                executeRequete("UPDATE commande SET etat=\"$_POST[etat]\" WHERE id_commande = $_POST[id_commande];");
                // mail('user@adresse.mail', 'Votre commande', 'Votre commande est en cours');
            }
            ?>

            <?php require_once("../inc/bas.inc.php"); ?>

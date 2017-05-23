<?php
require_once("../inc/init.inc.php");
# ----------------- TRAITEMENT PHP
if (!internauteEstConnecteEtEstAdmin()) {
    header("location:../connexion.php");
}

require_once("../inc/haut.inc.php");?>
<?php  ?>
<!-- Création du formulaire pour trier les produits -->
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
// Si l'utilisateur a cliqué sur tri, on récupère les données triées en fonction du $_POST
if (isset($_POST['tri'])) {
    $commandes = executeRequete("SELECT commande.id_commande, commande.date_enregistrement, commande.montant, membre.pseudo, membre.adresse, membre.ville, membre.code_postal, commande.etat
        FROM commande, membre
        WHERE commande.id_membre = membre.id_membre
        ORDER BY $_POST[tri]
        ");
    }
    // Sinon, on ne tri pas
    else {
        $commandes = executeRequete("SELECT commande.id_commande, commande.date_enregistrement, commande.montant, membre.pseudo, membre.adresse, membre.ville, membre.code_postal, commande.etat
            FROM commande, membre
            WHERE commande.id_membre = membre.id_membre
            ");
        }

        $contenu .= '<table border="1"><tr>';
        // On génère les entêtes des colonnes du tableau
        while ($colonne = $commandes->fetch_field()) {
            $contenu .= '<th>' . $colonne->name . ' </th>';
        }
        $contenu .= '<th> Détail commande</th>';
        $contenu .= '<th> Modifier état </th>';

        // On parcourt les résultats pour remplir le tableau
        while ($ligne = $commandes->fetch_assoc()) { # parcours les elements de ma ligne
            $id_commande;
            // On crée un formulaire par ligne pour pouvoir modifier la commande
            $contenu .= "<form action=\"$_SERVER[PHP_SELF]\" method=\"POST\">";
            $contenu .= '<tr>';
            foreach ($ligne as $item => $value) {
                if ($item == "id_commande") {
                    $id_commande = $value;
                    // On crée un input invisible qui contient l'id de la commande
                    $contenu .= '<input type="hidden" name="id_commande" value="'. $id_commande .'"/>';
                }

                // Dans le select, on sélectionne par défaut l'état de la commande dans la BDD
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
            // Pour chaque commande, on sélectionne en détail les produits correspondant
            $commande = executeRequete("SELECT produit.id_produit, produit.titre, produit.reference, produit.photo, produit.prix, details_commande.quantite
                FROM commande, produit, details_commande
                WHERE commande.id_commande = ". $id_commande ." AND details_commande.id_commande = ". $id_commande ." AND details_commande.id_produit = produit.id_produit
                ");
                $contenu .= "<td>";
                $contenu .= '<table border="1"><tr>';

                // On génère les entêtes des colonnes du tableau
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
            // On fait un addition du montant de toutes les commandes pour avoir le chiffre d'affaire
            $chiffre = executeRequete("SELECT SUM(montant) as montant FROM commande");
            $chiffre2 = $chiffre->fetch_object();

            $contenu .= '<p>Chiffre d\'affaire : '.$chiffre2->montant.'€</p>';
            echo $contenu;

            // On modifie l'état de la commande
            if (isset($_POST['etat'])) {
                executeRequete("UPDATE commande SET etat=\"$_POST[etat]\" WHERE id_commande = $_POST[id_commande];");
                // mail('user@adresse.mail', 'Votre commande', 'Votre commande est en cours');
            }
            ?>

            <?php require_once("../inc/bas.inc.php"); ?>

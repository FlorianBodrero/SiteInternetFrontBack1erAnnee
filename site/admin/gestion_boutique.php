<?php
require_once("../inc/init.inc.php");
# ----------------- TRAITEMENT PHP
if (!internauteEstConnecteEtEstAdmin()) {
    header("location:../connexion.php");
}

# ------------------ SUPPRESSION
if (isset($_GET ['action']) && $_GET['action'] == 'suppression') {
    $resultat = executeRequete("SELECT * FROM produit WHERE id_produit = $_GET[id_produit]");
    $produit_a_supprimer = $resultat->fetch_assoc();
    $chemin_photo_a_supprimer = $_SERVER ['DOCUMENT_ROOT'] . $produit_a_supprimer['photo'];
    if (!empty($produit_a_supprimer['photo'] && file_exists($chemin_photo_a_supprimer))) {
        unlink($chemin_photo_a_supprimer);
        executeRequete("DELETE FROM produit WHERE id_produit = $_GET[id_produit]");
        $contenu .= '<div class="validation"> Suppression terminée </div>';
        $_GET['action'] = 'affichage';


    }
}


if (!empty($_POST)) {
    $photo_bdd = "";
    if (!empty($_FILES['photo']['name'])) {
        $nom_photo = $_POST['reference'] . '_' . $_FILES['photo']['name'];
        $photo_bdd = RACINE_SITE . "photo/$nom_photo";
        $photo_dossier = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . "photo/$nom_photo";
        copy($_FILES['photo']['tmp_name'], $photo_dossier);
    }
    foreach ($_POST as $indice => $valeur) {
        $_POST[$indice] = htmlentities(addslashes($valeur));
    }
    executeRequete("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, publique, photo, prix, stock) VALUES ('$_POST[reference]','$_POST[categorie]','$_POST[titre]','$_POST[description]','$_POST[couleur]','$_POST[taille]','$_POST[publique]','$photo_bdd','$_POST[prix]','$_POST[stock]' )");
    $contenu .= '<div class="validation"> Le produit a bien été ajouté</div>';
}

//Lien produit
$contenu .= '<a href="?action=affichage"> Afficher les produits </a>';
$contenu .= '<a href="?action=ajout"> Ajouter les produits </a>';
//Afficher produit
if (isset($_GET['action']) && $_GET ['action'] == 'affichage') {
    $resultat = executeRequete("SELECT * FROM produit");
    $contenu .= '<h2> Affichage des produits </h2>';
    $contenu .= 'Nombre de produits dans la boutique :' . $resultat->num_rows;
    $contenu .= '<table border="1"><tr>';
    while ($colonne = $resultat->fetch_field()) { # recupére l'ensemble des colonnes (11 avec l'id)
        $contenu .= '<th>' . $colonne->name . ' </th>';
    }
    $contenu .= '<th> Suppression </th>';
    $contenu .= '<th> Modication </th>';
    while ($ligne = $resultat->fetch_assoc()) { # parcours les elements de ma ligne
        $contenu .= '<tr>';
        foreach ($ligne as $item => $value) {
            if ($item == "photo") {
                $contenu .= '<td> <img src="' . $value . '" width = "70px" height = "70px"/></td>';
            } else {
                $contenu .= '<td>' . $value . '</td>';
            }
        }
        $contenu .= '<td> <a href="?action=modifcation&id_produit = ' . $ligne['id_produit'] . '" onclick="return(confirm ("En etes vous certain ?"));"><img src="../inc/img/edit.png"/></a> </td>';
        $contenu .= '<td> <a href="?action=suppression&id_produit = ' . $ligne['id_produit'] . '" ><img src="../inc/img/delete.png"/></a> </td>';
    }

}

require_once("../inc/haut.inc.php");
echo $contenu;
?>
<?php if (isset($_GET['action']) && $_GET['action'] == 'ajout' || $_GET['action'] == 'modification') {
if (isset($_GET['id_produit'])) {
    $resultat = executeRequete("SELECT * FROM produit WHERE id_produit = $_GET[id_produit]");
    $produit_actuel = $resultat -> fetch_assoc();

}
echo '<h1> Formulaire Produits </h1>
    <form method = "post" enctype = "multipart/form-data" action = "';
echo $_SERVER['PHP_SELF'];


echo '" 
        <label for="reference" > Reference </label >
        <input type = "text" id = "reference" value="';if(isset($produit_actuel['reference'])) echo $produit_actuel['reference']; echo '" name = "reference" placeholder = "la référence du produit" /> <br />

        <label for="categorie" > Categorie </label >
        <input type = "text" id = "categorie" name = "categorie" placeholder = "la categorie du produit" /> <br />

        <label for="titre" > Titre </label >
        <input type = "text" id = "titre" name = "titre" placeholder = "Le titre du produit" /> <br />

        <label for="couleur" > Couleur </label >
        <input type = "text" id = "couleur" name = "couleur" placeholder = "la couleur du produit" /> <br />

        <label for="description" > Description</label >
        <textarea id = "description" name = "description" placeholder = "la description du produit" > </textarea > <br />

        <label for="taille" > Taille </label >
        <select name = "taille" >
            <option value = "S" > S</option >
            <option value = "M" > M</option >
            <option value = "L" > L</option >
            <option value = "XL" > XL</option >
        </select >

        <label for="publique" > Publique</label >
        <input type = "radio" name = "publique" value = "h" checked /> Homme
        <input type = "radio" name = "publique" value = "f" /> Femme
        <input type = "radio" name = "publique" value = "m" /> Mixte <br />

        <label for="photo" > Photo</label >
        <input type = "file" id = "photo" name = "photo" /> <br />

        <label for="prix" > Prix </label >
        <input type = "text" id = "prix" name = "prix" placeholder = "le prix du produit" /> <br />


        <label for="stock" > Stock </label >
        <input type = "text" id = "stock" name = "stock" placeholder = "la quantité en stock" /> <br />
        <input type = "submit" value = "envoyer" >
    </form >';
    
};
<?php require_once("../inc/bas.inc.php"); ?>


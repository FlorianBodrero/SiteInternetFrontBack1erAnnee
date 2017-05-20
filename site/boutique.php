<?php
require_once("inc/init.inc.php");
//--------------------------------- AFFICHAGE HTML ---------------------------------//
?>
<?php require_once("inc/haut.inc.php"); ?>


<h1>Bienvenu sur e-Ynov</h1>
<br/>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<label for="motcle">Rechercher : </label>
<input type="text" name="motcle" placeholder="Tapez un mot" size="20px">
<input type="submit" name="rechercher" value="Rechercher">
</form>
<?php 
////// SANS RECHERHCE ON AFFICHE TOUT LES PRODUITS //////
if(empty($_POST['motcle'])){
$resultat = executeRequete("SELECT * FROM produit");
    $contenu .= '<h2> Affichage des produits </h2>';
    $contenu .= 'Nombre de produits dans la boutique :' . $resultat->num_rows;
    $contenu .= '<table border="1"><tr>';
    while ($colonne = $resultat->fetch_field()) { # recupére l'ensemble des colonnes (11 avec l'id)
	    if ($colonne->name != 'id_produit' && $colonne->name != 'reference') {
	        $contenu .= '<th>' . $colonne->name . ' </th>';
	   	    }
	   	    
    }
    while ($ligne = $resultat->fetch_assoc()) { # parcours les elements de ma ligne
        $contenu .= '<tr>';
        foreach ($ligne as $item => $value) {
        	if ($item != 'id_produit' && $item != 'reference') {
	            if ($item == "photo") {
	                $contenu .= '<td> <img src="inc/img/photoProduit/' . $value . '" width = "70px" height = "70px"/></td>';
	            } else {
	                $contenu .= '<td>' . $value . '</td>';
	            }
        	}
        }
        $contenu .= '<td> <u><a href="?action=panier&id_produit = ' . $ligne['id_produit'] . '" onclick="return(alert ("Ajouté au panier"));">Ajouter au panier </a></u> </td>';

    }

        $contenu .= '</table>';
echo $contenu;
}


////// ON COMMENCE LA RECHERCHE //////

else{
	$motcle = explode(" ", $_POST['motcle']);
	echo '<p><strong>Les produits correspondants à votre recherche : </strong> " '.$_POST['motcle'].' "</p>';
	
	$resultat = executeRequete("SELECT * FROM produit");
	$contenu .= '<h2> Affichage des produits </h2>';
    $contenu .= '<table border="1"><tr>';
    $data = array();
    $nombre =0;
    while ($colonne = $resultat->fetch_field()) { # recupére l'ensemble des colonnes (11 avec l'id)
	    if ($colonne->name != 'id_produit' && $colonne->name != 'reference') {
	        $contenu .= '<th>' . $colonne->name . ' </th>';
	   	    }
	   	    
    }
	
	foreach ($motcle as $word) {
		$resultat = executeRequete("SELECT * FROM produit WHERE categorie LIKE '%".$word."%' OR titre LIKE '%".$word."%' ");
    while ($ligne = $resultat->fetch_assoc()) { # parcours les elements de ma ligne
        $contenu .= '<tr>';
        if (!in_array($ligne, $data)) {
        	$nombre ++;
	        foreach ($ligne as $item => $value) {

	        	if ($item != 'id_produit' && $item != 'reference') {
			        if ($item == "photo") {
				            $contenu .= '<td> <img src="inc/img/photoProduit/' . $value . '" width = "70px" height = "70px"/></td>';
				    } else {
				            $contenu .= '<td>' . $value . '</td>';
						}	           
				}
			    array_push($data, $ligne);
			}
        }
        for ($a =0; $a < $nombre; $a++){
        $contenu .= '<td> <u><a href="?action=panier&id_produit = ' . $ligne['id_produit'] . '" onclick="return(alert ("Ajouté au panier"));">Ajouter au panier </a></u> </td>';
   		 }
   		 $nombre = 0;
    }
    

    }

    $contenu .= '</table>';
	echo $contenu;

	
}

?>
<?php require_once("inc/bas.inc.php"); ?>

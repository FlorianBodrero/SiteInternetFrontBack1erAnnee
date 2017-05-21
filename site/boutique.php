<?php
require_once("inc/init.inc.php");
//--------------------------------- AFFICHAGE HTML ---------------------------------//
?>
<?php require_once("inc/haut.inc.php"); ?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<label for="motcle">Rechercher : </label>
<input type="text" name="motcle" placeholder="Tapez un mot" size="20px">
<input type="submit" name="rechercher" value="Rechercher">
</form>
<?php
////// SANS RECHERHCE ON AFFICHE TOUT LES PRODUITS //////
if(empty($_POST['motcle'])){
    if (empty($_GET['page']) || $_GET['page'] == 1) {
        $resultat = executeRequete("SELECT * FROM produit LIMIT 0, 6");
    }
    else {
        $resultat = executeRequete("SELECT * FROM produit LIMIT $_GET[page]-1, 6");
    }

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
	$motcle = explode(" ", $_POST['motcle']); //DISPOSER EN TABLEAU LES MOTS CLES
	echo '<p><strong>Les produits correspondants à votre recherche : </strong> " '.$_POST['motcle'].' "</p>';

    if (empty($_GET['page']) || $_GET['page'] == 1) {
        $resultat = executeRequete("SELECT * FROM produit LIMIT 0, 6");
    }
    else {
        $resultat = executeRequete("SELECT * FROM produit LIMIT $_GET[page]-1, 6");
    }
	$contenu .= '<h2> Affichage des produits </h2>';
    $contenu .= '<table border="1"><tr>';

    while ($colonne = $resultat->fetch_field()) { # AFFICHER LES COLONNES
	    if ($colonne->name != 'id_produit' && $colonne->name != 'reference') {
	        $contenu .= '<th>' . $colonne->name . ' </th>';
	   	    }
    }

	$req = "SELECT * FROM produit WHERE";
	foreach ($motcle as $word) { //BUILD LA REQUETE A L(AIDE DE SMOTS CLES)
		 $req .= " (titre LIKE '%".$word."%' OR categorie LIKE '%".$word."%') AND";
	}
	$req = preg_replace("#AND$#", "", $req);

	$resultat = executeRequete($req);
    while ($ligne = $resultat->fetch_assoc()) { // CONSTUIRE LE TABLEAU EQUIVALENT A LA REQUETE CRÉÉE
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

		// AJOUTER UNE CASE AJOUTER AU PANIER A LA FIN DE CHAQUE ARTICLE
        $contenu .= '<td> <u><a href="?action=panier&id_produit = ' . $ligne['id_produit'] . '" onclick="return(alert ("Ajouté au panier"));">Ajouter au panier </a></u> </td>';
    }

    $contenu .= '</table>';
	echo $contenu;
}

?>
<?php require_once("inc/bas.inc.php"); ?>

<!-- $em = $this->getDoctrine()->getEntityManager();

		$article = $em->getRepository('AppBundle:Article');

		// On r�cupére le nombre d'articles, puis on définit le nombre d'articles par pages
		$pages = $article->getTotal();
		$articles_page = 6;
		$nb_pages = ceil($pages/$articles_page);
		$offset= ($page-1) * $articles_page;

        if( $page < 1 OR $page > $nb_pages AND $nb_pages >= 1 )
        {
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }

		$articles = $article->findBy(
			array(),
			array('createdAt' => 'desc'),
			$articles_page,
			$offset
		);

        return $this->render('@App/Newsletter/index.html.twig', array(
			'articles' => $articles,
			'page' => $page,
			'nb_pages' => $nb_pages
		)); -->

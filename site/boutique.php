<?php
require_once("inc/init.inc.php");
require_once("inc/haut.inc.php");?>


<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="motcle">Rechercher : </label>
    <input type="search" name="motcle" placeholder="Tapez un mot" size="20px">
    <input type="submit" name="rechercher" value="Rechercher">
</form>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div style="position:relative; margin:auto; width:90%; margin-bottom: 15px;">
        <span style="position:absolute; color:red; border:1px solid blue; min-width:100px;">
            <span id="myValue"></span>
        </span>
        <input name="range" value="15" type="range" id="myRange" max="150" min="0" style="width:50%">
    </div>
    <input type="submit" name="prixMax" value="Prix maximum">
</form>

<?php
//--------------------------------- TRAITEMENTS PHP ---------------------------------//
//--- AFFICHAGE DES CATEGORIES ---//
////// SANS RECHERHCE ON AFFICHE TOUT LES PRODUITS //////
if (!isset($_POST['range'])) {
    $_POST['range'] = 150;
}
if(empty($_POST['motcle'])){
    if (empty($_GET['page'])) {
        $_GET['page'] = 1;
        $resultat = executeRequete("SELECT * FROM produit WHERE prix <= $_POST[range] LIMIT 0, 6");
    }
    elseif ($_GET['page'] < 1) {
        header("location:boutique.php");
    }
    else {
        $offset = ($_GET['page']-1)*6;
        $resultat = executeRequete("SELECT * FROM produit WHERE prix <= $_POST[range] LIMIT $offset, 6");
    }
    $nb_pages = ceil($resultat->num_rows/6);

    if($_GET['page'] > $nb_pages && $nb_pages >= 1)
    {
        header("location:boutique.php");
    }

    $categories_des_produits = executeRequete("SELECT DISTINCT categorie FROM produit");
    $contenu .= '<div class="boutique-gauche">';
    $contenu .= "<ul>";
    while($cat = $categories_des_produits->fetch_assoc())
    {
    	$contenu .= "<li><a href='?categorie="	. $cat['categorie'] . "'>" . $cat['categorie'] . "</a></li>";
    }
    $contenu .= "</ul>";
    $contenu .= "</div>";
    // $categories_des_produits = executeRequete("SELECT DISTINCT categorie FROM produit");
    // $contenu .= '<div class="boutique-gauche">';
    // $contenu .= "<ul>";
    // while($cat = $categories_des_produits->fetch_assoc())
    // {
    // 	$contenu .= "<li><a href='?categorie="	. $cat['categorie'] . "'>" . $cat['categorie'] . "</a></li>";
    // }
    // $contenu .= "</ul>";
    // $contenu .= "</div>";
    // //--- AFFICHAGE DES PRODUITS ---//
    // $contenu .= '<div class="boutique-droite">';
    // if(isset($_GET['categorie']))
    // {
    // 	$donnees = executeRequete("SELECT id_produit,reference,titre,photo,prix FROM produit WHERE categorie='$_GET[categorie]'");
    // 	while($produit = $donnees->fetch_assoc())
    // 	{
    // 		$contenu .= '<div class="boutique-produit">';
    // 		$contenu .= "<h3>$produit[titre]</h3>";
    // 		$contenu .= "<a href=\"fiche_produit.php?id_produit=$produit[id_produit]\"><img src=\"inc/img/photoProduit/$produit[photo]\" width=\"100\" height=\"100\" /></a>";
    // 		$contenu .= "<p>$produit[prix] €</p>";
    // 		$contenu .= '<a href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '">Voir la fiche</a>';
    // 		$contenu .= '</div>';
    // 	}
    // }
    // $contenu .= '</div>';
}

////// ON COMMENCE LA RECHERCHE //////

else{
    $contenu .= '<p><strong>Les produits correspondants à votre recherche : </strong> " '.$_POST['motcle'].' "</p>';

    $motcle = explode(" ", $_POST['motcle']); //DISPOSER EN TABLEAU LES MOTS CLES
    $req = "SELECT * FROM produit WHERE";
    foreach ($motcle as $word) { //BUILD LA REQUETE A L(AIDE DE SMOTS CLES)
        $req .= " (titre LIKE '%".$word."%' OR categorie LIKE '%".$word."%') AND";
    }
    $req = preg_replace("#AND$#", "", $req);

    $resultat = executeRequete($req);

    $contenu .= '<div class="boutique-droite">';

    	while($produit = $resultat->fetch_assoc())
    	{
    		$contenu .= '<div class="boutique-produit">';
    		$contenu .= "<h3>$produit[titre]</h3>";
    		$contenu .= "<a href=\"fiche_produit.php?id_produit=$produit[id_produit]\"><img src=\"inc/img/photoProduit/$produit[photo]\" width=\"100\" height=\"100\" /></a>";
    		$contenu .= "<p>$produit[prix] €</p>";
    		$contenu .= '<a href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '">Voir la fiche</a>';
    		$contenu .= '</div>';
    	}
    $contenu .= '</div>';
}


//--- AFFICHAGE DES PRODUITS ---//
$contenu .= '<div class="boutique-droite">';
if(isset($_GET['categorie']))
{
	$donnees = executeRequete("SELECT id_produit,reference,titre,photo,prix FROM produit WHERE categorie='$_GET[categorie]'");
	while($produit = $donnees->fetch_assoc())
	{
		$contenu .= '<div class="boutique-produit">';
		$contenu .= "<h3>$produit[titre]</h3>";
		$contenu .= "<a href=\"fiche_produit.php?id_produit=$produit[id_produit]\"><img src=\"inc/img/photoProduit/$produit[photo]\" width=\"100\" height=\"100\" /></a>";
		$contenu .= "<p>$produit[prix] €</p>";
		$contenu .= '<a href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '">Voir la fiche</a>';
		$contenu .= '</div>';
	}
}
$contenu .= '</div>';
//--------------------------------- AFFICHAGE HTML ---------------------------------//
echo $contenu;
require_once("inc/bas.inc.php");
?>


<script>
var myRange = document.querySelector('#myRange');
var myValue = document.querySelector('#myValue');
var off = myRange.offsetWidth / (parseInt(myRange.max) - parseInt(myRange.min));
var px =  ((myRange.valueAsNumber - parseInt(myRange.min)) * off) - (myValue.offsetParent.offsetWidth / 2);

myValue.parentElement.style.left = px + 'px';
myValue.parentElement.style.top = myRange.offsetHeight + 'px';
myValue.innerHTML = 15 + '€';

myRange.oninput =function(){
    let px = ((myRange.valueAsNumber - parseInt(myRange.min)) * off) - (myValue.offsetWidth / 2);
    myValue.innerHTML = myRange.value + '€';
    myValue.parentElement.style.left = px + 'px';
};
</script>

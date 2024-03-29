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
if (!isset($_POST['range'])) { // tranche de prix max
    $_POST['range'] = 150;
}
 if(empty($_POST['motcle'])){
//     if (empty($_GET['page'])) {
//         $_GET['page'] = 1;
//         $resultat = executeRequete("SELECT * FROM produit WHERE prix <= $_POST[range] LIMIT 0, 6"); //afficher maximum 6 éléments
//     }
//     elseif ($_GET['page'] < 1) { // si pas de pages, ne pas écrire le nombre de pages
//         header("location:boutique.php");
//     }
//     else {
//         $offset = ($_GET['page']-1)*6; // nombre de pages de 0 à 5
//         $resultat = executeRequete("SELECT * FROM produit WHERE prix <= $_POST[range] LIMIT $offset, 6");
//     }
//     $nb_pages = ceil($resultat->num_rows/6); //arrondir le nombre de pages

//     if($_GET['page'] > $nb_pages && $nb_pages >= 1) // éviter bugs si les pages sont plus nombreuses que prévues
//     {
//         header("location:boutique.php");
//     }

    $categories_des_produits = executeRequete("SELECT DISTINCT categorie FROM produit");
    $contenu .= '<div class="boutique-gauche">';
    $contenu .= "<ul>";
    while($cat = $categories_des_produits->fetch_assoc())
    {
    	$contenu .= "<li><a href='?categorie="	. $cat['categorie'] . "&page=1'>" . $cat['categorie'] . "</a></li>";
    }
    $contenu .= "</ul>";
    $contenu .= "</div>";
}

////// ON COMMENCE LA RECHERCHE //////

else{
    $contenu .= '<p><strong>Les produits correspondants à votre recherche : </strong> " '.$_POST['motcle'].' "</p>';

    $motcle = explode(" ", $_POST['motcle']); //DISPOSER EN TABLEAU LES MOTS CLES
    $req = "SELECT * FROM produit WHERE";
    foreach ($motcle as $word) { //BUILD LA REQUETE A L'AIDE DES MOTS CLES
        $req .= " (titre LIKE '%".$word."%' OR categorie LIKE '%".$word."%') AND";
    }
    $req = preg_replace("#AND$#", "", $req); //supprimer le dernier AND

    $resultat = executeRequete($req); //executer la reqête finale

    $contenu .= '<div class="boutique-droite">';

    	while($produit = $resultat->fetch_assoc()) //afficher lors d'une recherche
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
	
    if (empty($_GET['page'])) {
        $_GET['page'] = 1;
        $resultat = executeRequete("SELECT * FROM produit WHERE prix <= $_POST[range] AND categorie='$_GET[categorie]' LIMIT 0, 6"); //afficher maximum 6 éléments
    }
    elseif ($_GET['page'] < 1) { // si pas de pages, ne pas écrire le nombre de pages
        header("location:boutique.php"); 
    }
    else {
        $offset = ($_GET['page']-1)*6; // nombre de pages de 0 à 5
        $test = executeRequete("SELECT * FROM produit WHERE prix <= $_POST[range] AND categorie='$_GET[categorie]'");
        $resultat = executeRequete("SELECT * FROM produit WHERE prix <= $_POST[range] AND categorie='$_GET[categorie]' LIMIT $offset, 6");
    }
    $nb_pages = ceil($test->num_rows/6); //arrondir le nombre de pages

    if($_GET['page'] > $nb_pages && $nb_pages >= 1) // éviter bugs si les pages sont plus nombreuses que prévues
    {
        header("location:boutique.php");
    }

	while($produit = $resultat->fetch_assoc()) //affichage en cliquant sur le menu gauche
	{
		$contenu .= '<div class="boutique-produit">';
		$contenu .= "<h3>$produit[titre]</h3>";
		$contenu .= "<a href=\"fiche_produit.php?id_produit=$produit[id_produit]\"><img src=\"inc/img/photoProduit/$produit[photo]\" width=\"100\" height=\"100\" /></a>";
		$contenu .= "<p>$produit[prix] €</p>";
		$contenu .= '<a href="fiche_produit.php?id_produit=' . $produit['id_produit'] . '">Voir la fiche</a>';
		$contenu .= '</div>';
	}

	for ($i=1;$i<$nb_pages+1;$i++){
	echo '<a style="margin:10px;" href="?categorie='.$_GET['categorie'].'&page='.$i.'">Page '.$i.'</a>';
}
}
$contenu .= '</div>';

//--------------------------------- AFFICHAGE HTML ---------------------------------//
echo $contenu;
require_once("inc/bas.inc.php");
?>


<script> //script concernant la tranche de prix
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

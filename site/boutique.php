<?php
require_once("inc/init.inc.php");
//--------------------------------- AFFICHAGE HTML ---------------------------------//
?>
<?php require_once("inc/haut.inc.php"); ?>
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

    $contenu .= '<h2> Affichage des produits </h2>';
    $contenu .= 'Nombre de produits dans la boutique :' . $resultat->num_rows;
}

////// ON COMMENCE LA RECHERCHE //////

else{
    $contenu .= '<p><strong>Les produits correspondants à votre recherche : </strong> " '.$_POST['motcle'].' "</p>';

    $contenu .= '<h2> Affichage des produits </h2>';

    $motcle = explode(" ", $_POST['motcle']); //DISPOSER EN TABLEAU LES MOTS CLES
    $req = "SELECT * FROM produit WHERE";
    foreach ($motcle as $word) { //BUILD LA REQUETE A L(AIDE DE SMOTS CLES)
        $req .= " (titre LIKE '%".$word."%' OR categorie LIKE '%".$word."%') AND";
    }
    $req = preg_replace("#AND$#", "", $req);

    $resultat = executeRequete($req);
}
createTab($resultat, $contenu);
echo $contenu;

function createTab($resultat, &$contenu){
    $contenu .= '<table border="1"><tr>';

    while ($colonne = $resultat->fetch_field()) { # AFFICHER LES COLONNES
        if ($colonne->name != 'id_produit' && $colonne->name != 'reference') {
            $contenu .= '<th>' . $colonne->name . ' </th>';
        }
    }

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
}
?>
<?php require_once("inc/bas.inc.php"); ?>


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

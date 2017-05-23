<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 09/05/2017
 * Time: 09:17
 */

function executeRequete($req)
{
    global $mysqli;
    $resultat = $mysqli -> query($req);
    if (!$resultat){
        die('erreur sur la requete SQL. <br/>. Message : '.$mysqli -> error. "<br/> Code : ".$req);
    }
    return $resultat;

}

function debug ($var, $mode = 1)
{
    echo '<div style="background: orange; padding: 5px; float: right; clear: both;">';
    $trace = debug_backtrace();
    $trace = array_shift($trace); # on enlève la première dimension du tableau
    echo "Debug demandé dans le fichier : ". $trace['file']. " à la ligne". $trace['line']. "<hr/>";

    if ($mode === 1) {
        echo "<pre>".print_r($var); echo "</pre>";
    } else {
        echo "<pre>".var_dump($var); echo "</pre>";
    }

}

function internauteEstConnecte() {
    if (!isset($_SESSION['membre'])) {
        return false;
    } else {
        return true;
    }
}

function internauteEstConnecteEtEstAdmin() {
    if (internauteEstConnecte() && $_SESSION['membre']['statut'] == 1) {
        return true;
    }
    return false;
}

function creationDuPanier()
{
   if (!isset($_SESSION['panier']))
   {
      $_SESSION['panier']=array();
      $_SESSION['panier']['titre'] = array();
      $_SESSION['panier']['id_produit'] = array();
      $_SESSION['panier']['quantite'] = array();
      $_SESSION['panier']['prix'] = array();
   }
}

function ajouterProduitDansPanier($titre,$id_produit,$quantite,$prix)
{
	creationDuPanier();
    $position_produit = array_search($id_produit,  $_SESSION['panier']['id_produit']);
    if ($position_produit !== false)
    {
         $_SESSION['panier']['quantite'][$position_produit] += $quantite ;
    }
    else
    {
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['quantite'][] = $quantite;
		$_SESSION['panier']['prix'][] = $prix;
    }
}
//------------------------------------
function montantTotal()
{
   $total=0;
   for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
   {
      $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
   }
   return round($total,2);
}
//------------------------------------
function retirerproduitDuPanier($id_produit_a_supprimer)
{
	$position_produit = array_search($id_produit_a_supprimer,  $_SESSION['panier']['id_produit']);
	if ($position_produit !== false)
    {
		array_splice($_SESSION['panier']['titre'], $position_produit, 1);
		array_splice($_SESSION['panier']['id_produit'], $position_produit, 1);
		array_splice($_SESSION['panier']['quantite'], $position_produit, 1);
		array_splice($_SESSION['panier']['prix'], $position_produit, 1);
	}
}

function encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return base64_encode($encrypted_string);
}

function decrypt($encrypted_string, $encryption_key) {
    $encrypted_string = base64_decode($encrypted_string);
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return rtrim($decrypted_string, "\0");
}

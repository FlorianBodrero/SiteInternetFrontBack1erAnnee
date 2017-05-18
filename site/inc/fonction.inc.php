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


<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 09/05/2017
 * Time: 09:17
 */

//--------------------------------------BDDD

//----------------------------------------Connexion base de donneée site


$mysqli = new mysqli("localhost", "root", "", "site");

if($mysqli -> connect_error) die('Impossible de se connecté à la base de donnée'.$mysqli->connect_error);
$mysqli-> set_charset('utf8'); #regle l'encodage de la BDD

session_start();

define('RACINE_SITE', "/SiteInternetFrontBack1erAnnee/site/"); #en gros c'est le chemin absolu de notre site
$contenu = '';

require_once ("fonction.inc.php"); #inclu qu'une seule fois.

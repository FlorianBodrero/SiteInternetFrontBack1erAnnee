<?php
require_once("../inc/init.inc.php");
# ----------------- TRAITEMENT PHP
if (!internauteEstConnecteEtEstAdmin()) {
    header("location:../connexion.php");
}

require_once("../inc/haut.inc.php");

$membres = executeRequete("SELECT * FROM membre");

 echo '<div>';
    echo '<table style="border: solid black 1px; border-collapse: collapse">';
    echo '<thead>';
    echo '<th>ID</th>';
    echo '<th>Pseudo</th>';
    echo '<th>Nom</th>';
    echo '<th>Prénom</th>';
    echo '<th>Statut</th>';
    echo '</thead>';
    echo '<tbody>';
    while ($ligne = $membres->fetch_assoc()) {
        echo '<tr style="border: solid">';
        foreach ($ligne as $indice => $element) {
        	if ($indice == 'id_membre' || $indice == 'pseudo' || $indice == 'nom' || $indice == 'prenom' || $indice =='statut') {
            echo '<td style="border: solid">';
            echo '<span class="'.$ligne['id_membre'].'">'.$ligne[$indice].'</span';
            echo '</td>';
          	}

    	}
    	if($ligne['statut'] =='0'){
    		echo '<td>';
        echo "<a href=\"?upgrade=".$ligne['id_membre']."\">Améliorer</a>";
        echo '</td>';
    	
    	echo '<td>';
        echo "<a href=\"?membre=".$ligne['id_membre']."\">Supprimer</a>";
        echo '</td>';
		echo '</tr>';
		}
    }
	echo '</tbody>';
    echo '</table>';
    echo '</div>';
	echo '<div>';
    echo '</div>';


if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['membre'])) {
	executeRequete("DELETE FROM membre WHERE id_membre = ".$_GET['membre']." AND statut = '0' ");
	header("location:../admin/gestion_membre.php");
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['upgrade'])) {
	executeRequete("UPDATE membre SET statut = '1' WHERE id_membre = ".$_GET['upgrade']." AND statut = '0' ");
	header("location:../admin/gestion_membre.php");
}

require_once("../inc/bas.inc.php"); 


?>
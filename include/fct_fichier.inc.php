<?php

	//Fonction affichage taille fichier
	function affichage_taille_fichier($bytes, $decimals = 2) {
		$type_taille = array ('B','K','M','G');
  		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)).' '.$type_taille[$factor].'o'; 
	}
?>
<?php 
	// Capture du temp en début de page 
	$timestamp_debut = microtime(true);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Portail Projets Web- Epix &copy; 2016">
	<meta name="author" content="Epix">
	<link rel="icon" type="image/png" href="favicon.png" />

	<title>PPW - <?php echo $_SERVER['SERVER_NAME']; ?></title>

	<!-- Bootstrap core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Feuilles de style -->
	<link href="css/ppw.css" rel="stylesheet">
	<link href="css/icoMoon.css" rel="stylesheet">

	<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
	<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
	<script src="js/ie-emulation-modes-warning.js"></script>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				<a class="navbar-brand" href="#"><span class="icon icon-sphere bleu"></span> <span class="bleu">P</span>ortail <span class="bleu">P</span>rojets <span class="bleu">W</span>eb</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">				
					<li><a href="php/infophp.php" target="_blank"><span class="icon icon-cog"></span> Phpinfo</a></li>
					<li><a href="IcoMoonRef/Reference.html" target="'_blank"><span class="icon icon-IcoMoon"></span> IcoMoon</a></li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</nav>
	<!-- Liste des projets -->
	<div class="container">
		<div class="row">
			<ul class="thumbnails list-unstyled">
			<?php
				//Trouve le dossier courant
				$racine = getcwd();
				//Ajoute de l'emplacement des projets
				$dossier_projets = "/projets";
				$repertoire = $racine.$dossier_projets;

				//Scanne les projets 
				$liste_projet = scandir($repertoire);

				//On parcours les projets 
				foreach ($liste_projet as $projet) {

					//On ne prend que les répertoires qui ne sont pas cachés
					if (is_dir($repertoire.'/'.$projet) and (substr($projet, 0,1) <> '.' )) {
		
						//----- Bloc debug ------   
						//header('Content-type: text/plain');
						//echo("fichier : ".$repertoire.'/'.$fichier."\r");
						//-------------------------

						//Init des variables
						$arbo_projet = $repertoire.'/'.$projet;
						$nb_fichier = 0;
						$nb_fichier_code = 0;
						$type_php = 0;
						$type_css = 0;
						$type_js = 0;
						$type_image = 0;
						$type_autre = 0;
						$nb_dossier = 0;
						//Création des objets datetime
						$date_creation = new DateTime();
						$date_compare = new DateTime();
						$date_modification = new DateTime();
						//Init date modif pour comparaison
						$date_modification->setDate(1970,01,01);

						$projet_git = false;
			
						//Création de l'arborescence des fichiers à parcourir
						$liste_recursive = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($arbo_projet));

						//Parcours de la liste des fichiers/dossiers d'un projet
						foreach ($liste_recursive as $fichier) {
							//On ne prend pas les fichiers contenu dans l'arborescence lié à Git
							//On recherche dans le path si on trouve l'occurence .git
							if (strpos($fichier->getPathname(),'.git') === false) {
								//Traitement si le fichier est un dossier
								if ($fichier->isDir() and $fichier->getFilename() <> '..') {
										$nb_dossier++;     
								} else {
								//Traitement si le fichier n'est pas un dossier
									       if ($fichier->getFilename() <> '..') { $nb_fichier++;}
											
											//-----------------------------------
											//  Traitement des extensions
											//-----------------------------------
											// Comptage des fichiers de code en fonction de l'extension du fichier
											switch ($fichier->getExtension()) {
												case 'php': 
														$type_php++;
														$nb_fichier_code++;
														break;
												case 'css': 
												case 'less': 
														$type_css++; 
														$nb_fichier_code++;
														break;  
												case 'js': 
												case 'json': 
														$type_js++;
														$nb_fichier_code++;
														break;
												case 'jpg' : 
												case 'png' : 
												case 'gif' : 
														$type_image++; 
														break; 
												default: $type_autre++; break;
											}
											//-----------------------------------
											//  Traitement des dates
											//-----------------------------------
											// Recherche des dates de création et dernière modification du projet
											// en comparant les dates des fichiers trouvés dans le projet
											$date_compare->setTimestamp(filectime($fichier));                  
											//Recherche date de création
											if ($date_compare < $date_creation) {
												//On clone l'objet pour pouvoir le copier
												$date_creation = clone $date_compare;                
											}
											 if ($fichier->getFilename() <> '..') {
												$date_compare->setTimestamp(filemtime($fichier));											
												//Recherche date de dernière modification
												if ($date_compare > $date_modification) {
													$date_modification = clone $date_compare; 
												}               
											 } 
										}
							} else {								
								$projet_git = true;							
							}
					}
				?>
				<li class="col-md-3 col-sm-6 col-xs-12 ">
					<div class="thumbnail" style="padding: 0">
						<div class="caption">
							<h2>
									<a href="<?php echo('http://'.$_SERVER['HTTP_HOST'].'/'.$dossier_projets.'/'.$projet); ?>" target="_blank"><?php echo(ucfirst($projet))?></a>
									<?php if($projet_git == true) { ?>
									<small><span class="icon icon-git orange"></span></small>
									<?php } ?>
							</h2>
							<p>
								<b><?php echo(date_format($date_modification, "d.m.Y H:i:s")); ?></b><br/><small>Dernière modification</small>
							</p>
							<p>
								<b><?php echo(date_format($date_creation, "d.m.Y")); ?></b><br/><small>Date de création</small>
							</p>							
						</div>
						<div class="modal-footer" style="text-align: left">
							<div class="progress">
								<div 
									class="progress-bar progress-bar-striped" 
									style="width: <?php if($nb_fichier_code > 0){echo(round(($type_php*100/$nb_fichier_code)));} ?>%;"  
									data-toggle="tooltip" 
									data-placement="bottom" 
									title="PHP <?php echo(round(($type_php*100/$nb_fichier_code))); ?>%">
								</div>
								<div 
									class="progress-bar progress-bar-warning progress-bar-striped" 
									style="width:  <?php  if($nb_fichier_code > 0){echo(round(($type_css*100/$nb_fichier_code)));} ?>%" 
									data-toggle="tooltip" 
									data-placement="bottom" 
									title="CSS <?php echo(round(($type_css*100/$nb_fichier_code))); ?>%">
								</div>
								<div 
									class="progress-bar progress-bar-striped progress-bar-success" 
									style="width:  <?php  if($nb_fichier_code > 0){echo(round(($type_js*100/$nb_fichier_code)));} ?>%" 
									data-toggle="tooltip" 
									data-placement="bottom" 
									title="JS <?php echo(round(($type_js*100/$nb_fichier_code))); ?>%">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4 col-xs-4">
									<span class="badge bgplus" data-toggle="tooltip" data-placement="bottom" title="<?php echo($nb_dossier.' dossier');if($nb_dossier>1){echo('s');} ?>">
										<?php echo($nb_dossier.' '); ?>
										<span class="icon icon-folder-open"></span>
									</span>
								</div>
								<div class="col-sm-4 col-xs-4">
									<span class="badge" data-toggle="tooltip" data-placement="bottom" title="<?php echo($nb_fichier.' fichier');if($nb_fichier>1){echo('s');} ?>">
										<?php echo($nb_fichier.' '); ?>
										<span class="icon icon-file-text2"></span>
									</span>
								</div>
								<div class="col-sm-4 col-xs-4">
									<span class="badge" data-toggle="tooltip" data-placement="bottom" title="<?php echo($type_image.' image');if($type_image>1){echo('s');} ?>">
										<?php echo($type_image.' '); ?>
										<span class="icon icon-image"></span>
									</span>
								</div>								
							</div>
						</div>
					</div>
				</li>
			<?php 
			} 
		}
	?>
			</ul>
		</div>	
		<div class="row bar_temps">
			<div class="col-xs-4 col-md-5 text-left">
				<?php
				//Calcul et affichage de temps de génération de la page côté serveur
				$timestamp_fin = microtime(true);
				$temps_page = round($timestamp_fin - $timestamp_debut,2);
				echo ('Page générée en ' . $temps_page . ' seconde');
				if($temps_page>=2) { echo('s');}
				?>	
			</div>
			<div class="col-xs-4 col-md-2 text-center">	
				<div class="progress">
					<div class="progress-bar progress-bar" style="width:33%;">PHP</div>
					<div class="progress-bar progress-bar progress-bar-warning" style="width:34%">CSS</div>
					<div class="progress-bar progress-bar progress-bar-success" style="width:33%">JS</div>
				</div>			 	
			</div>
			<div class="col-xs-4 col-md-5 text-right">
			By Epix &copy; 2016
			</div>	
		</div>		 
	</div>							
	<!-- /.container -->

	<!-- Bootstrap core JavaScript
		================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
	<script type="text/javascript">
		window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')    
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();   
		});
	</script>
	<script src="js/bootstrap.min.js"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
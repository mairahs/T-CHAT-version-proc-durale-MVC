<?php
//Controleur
require_once 'commons/login_functions.php';
require_once 'models/salons.php';
require_once 'commons/controle_formulaire.php';

verifierConnexion(); // important car si on est pas connecté on ne peut pas accéder à cette page. la seule page sur laquelle on a pas fait cette vérif c'est sur la page d'inscription qui elle est accessible tout le temps

function afficherErreurs($champ){

	// je récupère $erreurs depuis mon fil d'exécution global(mon controleur)
	global $erreurs;

	// je vérifie que le nom du champ existe dans $erreurs et j'affiche l'erreur correspondante
	echo !empty($erreurs[$champ]) ? $erreurs[$champ] : '';
}
// faut pas engueuler l'ut la première fois qu'il arrive sur la page en lui affichant des messages d'erreurs alors qu'il n'a meme pas encore posté de données donc on va protéger le traitement de notre formulaire dans le !empty($_POST) donc on ne fait le traitement du formulaire que si un post a été envoyé et qu'il est non vide

$erreurs = array();

if(!empty($_POST)){



	//if(isset($_POST) && isset($_POST['nom']) && !empty($_POST['nom'])){

	  if(estPoste('nom')){

	  	//on vérifie qu'un nom a bien été posté et qu'il est non vide

		$nomSalon = trim($_POST['nom']);

		$idNouveauSalon = ajouterSalon($nomSalon);

		$_SESSION['messageSucces']='Félicitations, le salon '.trim($_POST['nom']).' a bien été créé';

		header("Location: salon.php?id=$idNouveauSalon");


	}else{

		$erreurs['nomSalon'] = "Le nom du salon est un champ obligatoire";
	}

}


 ?>
 <?php include 'incs/header.php'; ?>

 <header>
 	<h1>Accueil de T'Chat</h1>
 </header>
 <aside>
 	<?php include 'incs/menus.php'; ?>
 </aside>
 <main>
 	<form action ="ajouterSalon.php" method="POST">

 		<label for ="nom">Nouveau Salon</label>
 		<input type="text" name="nom" id="nom">
 		<input type="submit" name="send" value="Créer un salon" class="button">
 		<?php afficherErreurs('nomSalon'); ?>

 	</form>
 </main>

 <?php include 'incs/footer.php'; ?>
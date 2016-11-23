<?php
// ici commence notre contrôleur
// nous créons un "faux" jeu de données sous la forme d'un simple tableau PHP



// Si l'utilisateur n'est pas connecté impossibilité d'accéder à index.php redirection vers login.php

/*

	if(!isset($_SESSION['utilisateur'])){
	header('Location:login.php');
}
*/

//apres la ligne 11 on est sur que l'utilisateur sera FORCEMENT connecté donc on peut très bien récupérer notre utilisateur dans la vue

require_once 'commons/login_functions.php';

//J'inclus mon fichier de modèle uniquement si celui-ci n'est pas déjà inclu
require_once 'models/salons.php';

verifierConnexion();

//var_dump($_SERVER);
////var_dump($salons);

// Nous appelons la méthode array_keys pour récupérer les clés de $salons
// $nomsSalon contiendra donc uniquement les noms de mes salons

//$nomsSalon = getNomsSalons();
$nombreSalons = getNombreSalons();
if($nombreSalons === FALSE){
	$nombreSalons = 0;
}

$plurielSalon = $nombreSalons <= 1 ? '' : 's';

$utilisateurConnecte = getUtilisateur();
// ici se termine le contrôleur
var_dump($utilisateurConnecte);

?>

<!-- Ici commence la vue -->

<?php include 'incs/header.php'; ?>
		<header>
			<h1>Accueil de T'Chat</h1>
		</header>

		<aside>

			<?php include 'incs/menus.php'; ?>
			
		</aside>
			

				
		<main>

			<h2>Bienvenue dans T'Chat, <?php echo $utilisateurConnecte['pseudo']; ?></h2>
			<p>Le but de T'Chat est de proposer plusieurs salons au sein desquels vous pourrez poster vos messages.</p>

			<p>Il y a actuellement <?php echo $nombreSalons;?> salon<?php echo $plurielSalon;?> ouvert<?php echo $plurielSalon; ?>.</p>

			<p>Rejoignez dès à présent un salon !!!</p>

			<p> Si vous ne trouvez pas de salon à votre goût, créez-en un nouveau !!!</p>

			<p>
				<a href="ajouterSalon.php" class="button">Créer un nouveau salon</a>
			</p>

			
		</main>
	<!--  Ici se termine la vue  -->

<?php include'incs/footer.php'?>


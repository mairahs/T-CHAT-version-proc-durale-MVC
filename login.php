<?php

//session_start();
	//ici début du controlleur
	require_once 'commons/login_functions.php';
	require_once 'models/utilisateurs.php';
	require_once 'commons/start_session.php';
	
	//je demarre ma session pour avoir accès à la variable $_SESSION dans la suite du programme

	verifierConnexion();

	/*
		if(isset($_SESSION['utilisateur'])){

			//$infosUtilisateur = $_SESSION['utilisateur']; pas besoin de récupérer les infos ut tout de suite car on ne les utilise pas tout de suite redirige seulement vers index.php donc si la session existe déjà pas besoin de revenir sur le formulaire de connexion on redirige directement l'utilisateur
			header('Location:index.php');
		}
	*/

	//On va gérer l'envoi du formulaire et la connexion de l'utilisateur

	// Je vérifie l'existence d'un post et que le post contient un pseudo

	if(isset($_POST) && isset($_POST['pseudo'])){

		//on génère un tableau d'erreurs

		$erreurs = array();

		//!== comparaison stricte or != comparaison souple préférez la STRICTE cette précaution est d'autant plus nécessaire que PHP est un langage typé dynamiquement

		//Récupération de mon pseudo

		//je nettoie tous les espaces vides et les caractères invisibles

		

		$pseudo =trim($_POST['pseudo']);

		if($pseudo !== ""){

				// si le pseudoposté est non vide, on essaye de récupérer un utilisateur grâce à ce pseudo. on utilise donc la méthode getutilisareurparsonpseudo

				$utilisateur = getUtilisateurParSonPseudo($pseudo);

				//on ne connecte l'utilisateur que si on en a trouvé un avec ce pseudo

				if($utilisateur !== FALSE){

				//J'ai récupéré un utilisateur et je vérifie son mot de passe

				$motDePasseHashe = $utilisateur['mot_de_passe'];

				if(!empty($_POST['mot_de_passe']) && verifierMotDePasse($_POST['mot_de_passe'], $motDePasseHashe)){


						//Tout est bon je peux connecter l'utilisateur donc Création de session

						//$_SESSION['utilisateur'] = array('pseudo'=>$pseudo);cette fois on connecte l'ut en session avec toutes ses infos et plus seulement son pseudo
						//on ne stocke pas le mot de passe de l'utilisateur en session légalement on a pas le droit
						unset($utilisateur['mot_de_passe']);
						//on peut aussi écrire unset($_SESSION['utilisateur']['mot_de_passe']);
						$_SESSION['utilisateur'] = $utilisateur;

						//et je redirige vers index.php

						//header('Location: index.php');
						redirectPage('index');

				}else{

					$erreurs['mot_de_passe'] = "Mot de passe non reconnu";
				}
				



				}else{

					$erreurs['pseudo'] = " Ce pseudo n'a pas été trouvé en base";
				}
		

		}else{
			$erreurs['pseudo'] = "Ce champ est obligatoire";
		}

		
	}

	function afficherErreurs($champ){

		// je récupère $erreurs depuis mon fil d'exécution global(mon controleur)
		global $erreurs;

		// je vérifie que le nom du champ existe dans $erreurs et j'affiche l'erreur correspondante
		echo !empty($erreurs[$champ]) ? $erreurs[$champ] : '';
}


	//ce if doit être à ce niveau  et pas au nivo de la ligne 17 car, comme j'a écris la ligne 15 et bien la ligne 22 sera toujours vraie puisque je viens d'écrire la 17 et donc que l'ut vient juste de se connecter. Donc il faut que ça marche dans le cas ou l'ut s'est connecté il y longtemps

	

 include'incs/header.php'; 

 ?>
		<header>
			<h1>Connexion à T'Chat</h1>
		</header>

		<main>
			
			<h2>Connectez-vous à T'Chat</h2>

			<form action="login.php" method="POST">

				<p>
					<label for ="pseudo">Entrez votre pseudo:</label>
					<input type="text" name="pseudo" id="pseudo">
					<?php afficherErreurs('pseudo'); ?>
				</p>

				<p>
					<label for ="password">Entrez votre mot de passe:</label>
					<input type="password" name="mot_de_passe" id="password">
					<?php afficherErreurs('mot_de_passe'); ?>
				</p>

				<p>
					<input type="submit" value="Connectez-vous!" class="button">
					<a href="inscription.php" title="accédez à la page d'inscription">Pas encore inscrit ?</a>
				</p>

				
				
			</form>

		</main>
			
<?php include 'incs/footer.php'; ?>		
			

		

	
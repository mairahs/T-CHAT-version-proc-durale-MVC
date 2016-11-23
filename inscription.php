<?php 

//CONTROLEUR

require_once 'commons/controle_formulaire.php';
require_once 'models/utilisateurs.php';
require_once 'commons/url_functions.php';
require_once 'commons/start_session.php';

if(/*isset($_POST) && pas forcément nécessaire*/ !empty($_POST)){
	

	// je prépare les champs que je souhaite recevoir en les stockant dans un tableau de façon à parcourir ce tableau par la suite dans un foreach

	$champs = array('Pseudo'=>'pseudo','Mot de Passe'=>'mot_de_passe','Email'=>'email','Sexe'=>'sexe');

	//je crée un tableau d'erreur que je compte remplir au fur et à mesure

	$erreurs = array();

	//je parcours tous les champs de mon tableau de façon à contrôler l'existence et la non vacuité de chaque champ

	foreach($champs as $champBienEcrit=>$champ){

			if(!estPoste($champ)){

				// ajout d'un message d'erreur les crochets sont vides car on laisse PHP remplir lui-même l'index. sinon on peut aussi imposer un index en associant des noms de champs à des erreurs ce qui rend tout cela facilement identifiable

				//$erreurs[] = 'Le champ'.$champ.'est obligatoire.';
				$erreurs[$champ] = 'Le champ '.$champBienEcrit.' est obligatoire.';



			}


		}

		/*

				//Controle de la longueur de mon pseudo

		$longueurPseudo = strlen($_POST['pseudo']);

		if($longueurPseudo < 3 || $longueurPseudo > 50){

			$erreurs['pseudo'] = 'La longueur du pseudo doit être comprise entre 3 et 50 caractères'; // et dans ce cas le précédent message d'erreur précédent du pseudo sera effacé eeeet ce n'est pas grave puisque le 1er ne servira plus à rien
		}

		
		
	}

	*/


	//if(isset($_POST['pseudo']) && $_POST['pseudo'] !== ''){

		//traitement du pseudo
	//}

	//if(isset($_POST['mot_de_passe']) && $_POST['mot_de_passe'] !== ''){

		//traitement du mot de passe
	//}

	//if(isset($_POST['email']) && $_POST['email'] !== ''){

		//traitement du mot de passe
	//}

	$minPseudo = 3;
	$maxPseudo = 50;
	$minMotDePasse = 3;
	$maxMotDePasse = 50;

	if(!longueurEntre('pseudo',$minPseudo,$maxPseudo)){

		$erreurs['pseudo'] = 'La longueur du pseudo doit être comprise entre '.$minPseudo.' et '.$maxPseudo.' caractères';
	}

	if(!longueurEntre('mot_de_passe',$minMotDePasse,$maxMotDePasse)){

		$erreurs['mot_de_passe'] = 'La longueur du mot de passe doit être comprise entre '.$minMotDePasse.' et '.$maxMotDePasse.' caractères';
	}

	if(!emailValide('email')){
		$erreurs['email'] = 'Cet email n\'est pas un email valide.';

	}

	if(empty($_POST['sexe']) || !in_array($_POST['sexe'], ['homme','femme','non-defini'])){
		$erreurs['sexe'] = "Le sexe renseigné n'est pas valide";
	}

	// je vérifie qu' un avatar a ét envoyé
	if(!empty($_FILES['avatar']['tmp_name'])){

		//vérification du type MIME
		// on va s'assurer du type MIME envoyé par $_FILES cette methode est plus sécurisée que $_FILES['avatar']
		if(is_uploaded_file($_FILES['avatar']['tmp_name'])){

			//je récupère le type MIME de façon sécurisée

			$typeMime = mime_content_type($_FILES['avatar']['tmp_name']);

			//je génère un tableau de type valides
			$typesValides = array('image/jpeg','image/png','image/gif');

			//Je vérifie que mon type mime fait partie des types MIME valides
			if(in_array($typeMime,$typesValides)){

				// je vérifie que mon fichier n'est pas trop volumineux
				$maxSize = 1000000; // 1 mo ce qui est largement suffisant pour un avatar

				if($_FILES['avatar']['size'] <= $maxSize){

					//j'ai fait toutes mes vérifications
					//je peux transférer mon fichier dans un dossier ou il ne sera pas détruit

					$nouveauNomFichier = md5(time().uniqid());
					// uniqid et time varie tout le temps et pour plus de sécurité on concatène les 2 et on fait un hasch dessus => sécurité MAX pour empecher que l'on ait 2 fichiers du même nom à chaque requête
					move_uploaded_file($_FILES['avatar']['tmp_name'], 'avatars/'.$nouveauNomFichier);
				}else{
					$erreurs['avatar'] = "Le fichier doit faire moins de 1Mo";
				}
			}
			else{
				$erreurs['avatar'] = "Le fichier doit être un jpg, un png ou un gif";
			}
		}
		else{

			$erreurs['avatar'] = "L'avatar ne s'est pas téléchargé correctement";
		}
		
	}

	// traitement du formulaire (insertion de l'utilisateur en base)
	if(empty($erreurs)){
		//on vérifie que l'email et le pseudo n'existe pas déjà en base

		if(!emailOuPseudoExistent($_POST['email'], $_POST['pseudo'])){

				// si le traitement du formulaire d'est effectué sans erreur
				// et si un fichier d'avatar a été uploadé, on appelle la fonction
				//ajouterUtilisateur avec le paramètre $avatar, sinon on l'appelle sans ce 2e parametre
				if(!empty($nouveauNomFichier)){
					$idNouvelUtilisateur = ajouterUtilisateur($_POST,$nouveauNomFichier);
				}else{
					// sinon le 2e parametre prendra la valeur par défaut
					//en l'ocurrence default.png
					$idNouvelUtilisateur = ajouterUtilisateur($_POST);
					//thibault n'a pas remis $infosUtilisateur et a préféré mettre $_POST qui contient exactement les mêmes infos et est en plus, en tant que variable superglobale car dans le contexte du controleur il existe
				}

				//la on  va stocker en session toutes les infos de l'utilisateur qui vient d'être ajouter en bdd, on veut connecter donc directement l'ut suite à son inscription donc il enregistrer ses infos postées. Or on a pas l'id de l'ut donc array_merge va mélanger les 2 tableaux dou array_merge

				//$_POST = array('pseudo'=>'Lala,'email'=>'lala@gmail.com,'mot_de_passe=>123, 'sexe'=>'homme')
				//array('id'=>$idNouvelUtilisateur ) ou alors $_POST['id'] = $idNouvelUtilisateur

				//on stocke en session les infos utilisateurs contenues dans le POST auxquelles on ajoute l'id qui vient d'être généré. Ce qui nous permet de connecter directement l'utilisateur
				$_SESSION['utilisateur'] = array_merge($_POST, array('id'=> $idNouvelUtilisateur));

				//tout ce bordel en fait c'est parce que on a pas accès à l'id de l'ut lorsqu'il se connecte
				var_dump($_SESSION['utilisateur']);
				redirectPage('index');

			}else{

				$erreurs['pseudo'] = "L'email et le pseudo existent déjà";
			}



		}
		
}


function afficherErreurs($champ){

	// je récupère $erreurs depuis mon fil d'exécution global(mon controleur)
	global $erreurs;

	// je vérifie que le nom du champ existe dans $erreurs et j'affiche l'erreur correspondante
	echo !empty($erreurs[$champ]) ? $erreurs[$champ] : '';
}

function afficherPosts($champ){
	// je vérifie qu une valeur a bien été postée pour ce nom de champ et si c'est le cas, j affiche cette valeur
	echo !empty($_POST[$champ]) ? $_POST[$champ] : '';
}

function afficherCheck($valeurAttendue){

	//si on a renseigné un sexe en POST et que la valeur rentrée en POST est celle qui est attendue par l'input radio, alors on veut cocher cet input

	echo !empty($_POST['sexe']) && $_POST['sexe'] == $valeurAttendue ? 'checked' : '';

}


			include 'incs/header.php';?>



			<h2 class='inscription'>Inscription d'un utilisateur</h2>

			<form action='inscription.php' method='POST' enctype = "multipart/form-data" class="inscription">
				
				<p>
					<label for="pseudo">Votre pseudo</label>
					<input type="text" name="pseudo" id="pseudo" placeholder="Entre 3 à 50 caractères" value="<?php afficherPosts('pseudo'); ?>">
					<?php afficherErreurs('pseudo'); ?>
				</p>
				<p>
					<label for="email">Votre email</label>
					<input type="email" name="email" id="email" value="<?php afficherPosts('email'); ?>">
					<?php afficherErreurs('email'); ?>
				</p>
				<p>
					<label for="password">Votre mot de passe</label>
					<input type="password" name="mot_de_passe" id="password" value="<?php afficherPosts('mot_de_passe'); ?>">
					<?php afficherErreurs('mot_de_passe'); ?>
				</p>
				<p>
					<label for="femme">Femme</label>
					<input type="radio" name="sexe" id="femme" value="femme" <?php afficherCheck('femme'); ?>>
				</p>
				<p>
					<label for="homme">Homme</label>
					<input type="radio" name="sexe" id="homme" value="homme" <?php afficherCheck('homme'); ?>>
				</p>
				<p>
					<label for="non-defini">Non-défini</label>
					<input type="radio" name="sexe" id="non-defini" value="non-defini" <?php afficherCheck('non-defini');?>>
					<?php afficherErreurs('sexe'); ?>
				</p>
				<p>
					<label for="avatar">Votre avatar</label>
					<input type="file" name="avatar" id="avatar">
					<?php afficherErreurs('avatar'); ?>
				</p>

				<p>
					<input type="submit" name="send" value="Je m'inscris" class="button">
				</p>

			</form>

	<?php include 'incs/footer.php'; ?>
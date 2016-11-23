<?php

	
	// Ici début de mon contrôleur

	// Cette page a pour but d'afficher les messages d'un salon en particulier
	// Nous aurons donc besoin de récupérer un et un seul salon 
	// Ce salon sera identifié de manière unique par un numéro appelé son 'id'

	require_once 'commons/url_functions.php';
	require_once 'commons/login_functions.php';
	require_once 'models/salons.php';
	require_once 'models/messages.php';

	// Je récupère l'id de mon salon qui été passé en get
	// Je le récupère donc via $_GET['id']
	// JE vérifie que $_GET['id']existe bel et bien
	if(isset($_GET) && isset( $_GET['id'] )) {
	    // je suis sûr que mon id existe en get
	    // je le récupère et le stocke dans une variable $idSalon
	    $idSalon = $_GET['id'];
	    
	    // je vérifie que $idSalon est un entier
	    if(ctype_digit($idSalon)) {
	        // j'ai vérifier que id était un entier
	       
	        
	        $nomSalonCourant = getNomSalonParSonId($idSalon);
	        // si $contenuSalon est null, cela signifie que l'utilisateur
	        // a rentré un id invalide en get. On le redirige gentiment 
	        // vers l'accueil.
	        if($nomSalonCourant === FALSE) // et non pas null car le fetch renvoit false en fait )
	         {
	            redirectPage('index');
	        }

	        //cette ligne sert à gérer le message de succès si le salon vient d'etre créé
	        if(isset($_SESSION['messageSucces'])){

	        	//je récupère le message correspondant à l'ajout du nouveau salon
	        	//si il existe
	        	$messageSucces = $_SESSION['messageSucces'];

	        	//je supprime immédiatement ce message en session de façon à ce qu'il ne soit pas réaffiché par la suite cependant $messageSucces existe toujours car c'est une copie
	        	unset($_SESSION['messageSucces']);
	        }
	        
	        // traitement de l'ajout d'un message on aurait pu aussi utilisé la fonction estPoste if(estPoste('message')) et dans ce cas on fait un require_once de controle formulaire
	        if(isset($_POST) 
	                && isset($_POST['corps']) 
	                && ! empty(trim($_POST['corps']))
	                ){
	            // je récupère l'utilisateur courant
	            $utilisateur = getUtilisateur();
	            
	            // puis je récupère son pseudo
	            //$pseudo = $utilisateur['pseudo'];
	        	$idUtilisateur = $utilisateur['id'];

	        	//on va donc insérer le nouveau message en base de données

	        	ajouterMessage($_POST['corps'],$idSalon,$idUtilisateur);

	            ///$contenuSalon[$pseudo] = $_POST['message'];
	        }
	        	// Je vais maintenant chercher à récupérer le salon correspondant
	        	$contenuSalon = getContenuSalonParSonId($idSalon);
	    } else {
	        redirectPage('index');
	    }
	    
	} else {
	    // dégage vers la page d'accueil
	    redirectPage('index');
	}
	//if(isset($_GET) 
	//        && isset( $_GET['id'] ) 
	//        && ctype_digit($_GET['id']) 
	//        && getSalonParSonId($_GET['id'])===NULL) {
	//    $contenuSalon = getSalonParSonId($_GET['id']);
	//} else {
	//    redirectPage('index');
	//}



?>

<!-- Ici commence la vue -->

<?php include 'incs/header.php'; ?>
		<header>
			<!-- <h1>Salon <?php //echo $nomSalonCourant; ?></h1> -->
		</header>

		<aside>

			<?php include 'incs/menus.php'; ?>
			
		</aside>

			<main>		
		
			  <?php echo isset($messageSucces) ? '<p>'.$messageSucces.'</p>' : ''; ?>
			 <h2>Bienvenue sur le salon <?php echo $nomSalonCourant; ?></h2> 
			<ol class ="messages">
				 <?php foreach ($contenuSalon as $message): ?> 
				<li><span class="personne"><?php echo htmlentities($message['pseudo']); ?></span> : <span class="message">"<?php echo htmlentities($message['corps']); ?>"</span></li> 
				 <?php endforeach; ?> 
			</ol>

			<!-- J'envoie mon formulaire d'ajout de message sur la page courante cela va me permettre d'ajouter mes messages à ce salon et pas à un autre via l'id -->
			<form class="form-message" action="salon.php?id=<?php echo $idSalon; ?>" method="POST">

				<textarea name ="corps"></textarea>
				<input type="submit" class="button" value="Poster son message">
				
			</form>
			
			
		</main>
	<!--  Ici se termine la vue  -->

<?php include'incs/footer.php'?>


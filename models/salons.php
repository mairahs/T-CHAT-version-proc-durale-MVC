<?php

require_once 'commons/init_db.php';

function getSalons(){

	$salons = [

					"Les supers héros du PHP"=>[

					'Googleman'=>'Tremblez devant la puissance de Googleman',
					'Pausewoman'=>'Mais pour qui il se prend lui ?',
					'Pauseman'=>"J'avoue. +1 @Pausewoman <3"
					], 

					"Les développeurs anonymes"=>[
					'Déborah'=>'Et moi j\'ai pas de surnom ?',
					'Sébastien'=>'C\'est vrai , n\'importe quoi cette formation ?',
					'Googleman'=>"Ahahaha"
					], 

					"Les sceptiques du premier rang"=>[
					'Gilles'=>"Tu as vu ce qui se dit qur les autres salons ? Bizarre, non ?",
					'Cédric'=>"Moi, j'ai renoncé à comprendre",
					'Christine'=>"Et sinon, quand est ce qu'on code ?",
					'Googleman'=>"Mouaaamoua !!!"

					]

			];

	return $salons;
		
}


function getNomsSalons(){
	/*
		$salons = getSalons(); // je suis obligée de le rapp ici car sinon $salons n'est pas connu à ce niveau
		$nomsSalon = array_keys($salons);

		return $nomsSalon;


	*/

		//on récupère notre connexion à la base de données
		$pdo = getDb();

		//ici on pas d'éléments reçus de l'utilisateur donc pas besoin de faire une requète préparée

		//j'exécute et parcoure la requête en une seule ligne
		return $pdo->query('SELECT nom,id FROM salons')->fetchAll();
}

function getNombreSalons(){

	$pdo = getDb();
	$resultat = $pdo->query('SELECT COUNT(*) as nb_salons FROM salons' )->fetch();
	//ici résultat doit contenir quelque chose comme array('nb_salons'=>5) car on veut obtenir un nombre et non pas un tableau comme la requete au-dessus
	if($resultat === FALSE){
		return FALSE;
	}else{
		return $resultat['nb_salons'];
	}
	//$salons = getSalons(); 
	//$nombreSalons = count($salons);
	//return $nombreSalons;
	//return count(getSalons());
}


/**
*cette fonction renvoit le nom d un salon préent en base grace à son id
*@param int $id
*@return string nom du salom

*/
function getNomSalonParSonId($id){
/*
	
		// Ensuite on récupère tous nos noms de salons qui est un array qui contient (0=>'Premier nom', 1=>'Deuxième nom' etc...)
		//notre stratégie va être de récupérer le nom du salon correspondant à l'id pour enfin accéder au salon lui-même en attendant d'avoir les bases de données
		$nomsSalon = getNomsSalons();
		//Je vérifie qu'il existe bien un nom de salon pour cet id

		if(isset($nomsSalon[$id])){

			//Il existe bien un nom de salon pour l'id qui a été renseigné
			//je récupère ce nom
			$nomSalon = $nomsSalon[$id];

			//je retourne le nom de mon salon

			return $nomSalon;
		}else{
			return null;
		}
*/

		$pdo = getDb();
		$requeteNom = $pdo->prepare('SELECT nom FROM salons WHERE id=:id_salon');
		$requeteNom->bindValue(':id_salon',$id);
		$requeteNom->execute();
		$resultat = $requeteNom->fetch();

		//resultat peut me renvoyer un array ou False donc on protège aussi
		if($resultat === FALSE) return FALSE;
		return $resultat['nom'];

}

/**
 * Cette fonction va récupérer un salon en fonction de son id
 *c'est à dire son numéro d'identification unique
 *@param int $id
 *return array $salon
 */
function getContenuSalonParSonId($id){
	/*

			// pour le moment on récupère d'abord tous nos salons
			$salons = getSalons();

			//Je récupère le nom de mon salon grace à ma super fonction
			$nomSalon = getNomSalonParSonId($id);

			//je vérifie que mon nom est non null c'est à dire que le salon existe

			if($nomSalon !== null){

				//je récupère le contenu du salon correspondant
				$contenuSalon = $salons[$nomSalon];

				// et je la retourne

				return $contenuSalon;
			}else{

				//si aucun n'est trouvé je ne retourne rien
				return null;
			}

	*/


		$pdo = getDb();
		$requeteContenu = $pdo->prepare('SELECT corps, date_creation,pseudo FROM messages JOIN utilisateurs ON `utilisateurs`.id = `messages`.id_utilisateur WHERE id_salon = :id_salon ORDER BY date_creation ASC,messages.id ASC');
			$requeteContenu->bindValue(':id_salon',$id);
			$requeteContenu->execute();
			return $requeteContenu->fetchAll();
	
}

/**
*cette fonction ajoute un nouveau salon en bdd et retourne l'id de ce nouveau salon
*@param string $nomSalon le nom du salon
*@return int l'id du nouveau salon

*/
function ajouterSalon($nomSalon){

	$pdo = getDb();

	$requeteAjout = $pdo->prepare('INSERT INTO salons (nom) VALUES(:nom)');
	$requeteAjout->bindValue(':nom',$nomSalon);
	$requeteAjout->execute();
	return $pdo->lastInsertId(); //bonne pratique car c'est une info qu'on a pas
}
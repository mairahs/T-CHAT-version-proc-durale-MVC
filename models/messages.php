<?php

// j'inclus le fichier init_db.php qui va nous servir à nous connecter à la bdd

require_once 'commons/init_db.php';
/**
*cette fonction a pour but de siplement rajouter un message en bdd
*@param string $message le contenu du message
*@param integer $idSalon l'id du salon dans lequel est posté le message
*@param integer $idUtilisateur l'id de l'utilisateur qui poste le *message

*/
function ajouterMessage($message, $idSalon, $idUtilisateur){
	$pdo = getDb();
	$dateCreation = date('Y-m-d H:i:s');
	$dateModification = $dateCreation;

    $requetePreparee = $pdo->prepare(" INSERT INTO messages (corps,date_creation, date_modification, id_utilisateur,id_salon) VALUES(:corps, :date_creation, :date_modification, :id_utilisateur, :id_salon)");

		
		$requetePreparee->bindValue(':corps', $message);
		$requetePreparee->bindValue(':date_creation', $dateCreation);
		$requetePreparee->bindValue(':date_modification', $dateModification );
		$requetePreparee->bindValue(':id_utilisateur', $idUtilisateur);
		$requetePreparee->bindValue(':id_salon', $idSalon);

		$requetePreparee->execute();
		return $pdo->lastInsertId();
	}

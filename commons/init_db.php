<?php

/**
*cette méthode instancie une connexion à la base de données de type PDO 
*et la retourne
*@return PDO une instance (objet) de connexion à la base de données
*/

function getDb(){
	//déclaration du dsn
	$dsn = 'mysql:host=localhost;dbname=t-chat;charset=utf8';

	//je déclare $pdo comme variable globale de façon à ce que pdo ne soit pas recréer plusieurs fois

	global $pdo;

	//on vérifie que odo n'a pas déjà été instanci ou créé
	if(!isset($pdo)){

			// Si pdo n'existe pas ou est nul on crée un nouveau pdo et on le stocke dans une variable $pdo qui est dorénavant globale
			//connexion en local seulement
			$pdo = new PDO($dsn,'root','');
	}

	return $pdo;
}
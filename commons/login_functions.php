<?php

require_once('commons/start_session.php');
require_once 'commons/url_functions.php';

function estConnecte(){

	return isset($_SESSION['utilisateur']);
}

function verifierConnexion(){

	//Cette fonction vérifie la connexion de l'utilisateur et redirige vers login.php si il n'est pas connecté

		//on récupère le nom de la page courante
		$page = getPage();

		// Si la page n'est pas login

		if($page != 'login'){

			// on redirige l'utilisateur vers login s'il n'est pas connecté


			//if(!isset($_SESSION['utilisateur'])){

				if(!estConnecte()){
				redirectPage('login');
			}

		} else{

			//si la page est login on le redirige vers index.php si il est connecté
			//if(isset($_SESSION['utilisateur'])){
				if(estConnecte()){
				redirectPage('index');
			}
		}


}

/**
 * Cette fonction retourne les informations de l'utilisateur connecté
 *si aucub=n utilisateur n'est connecté elle ne me retourne rien (null)
 *@return array les infos de l'utilisateur connecté
 */

function getUtilisateur(){
	if(estConnecte()){
		return $_SESSION['utilisateur'];
	}else{
		return NULL;
	}
}

//Cette fonction a pour but de déconnecter l'utilisateur courant
function deconnecterUtilisateur(){

		unset($_SESSION['utilisateur']);
	
}

function verifierMotdePasse($motDePasseEnClair,$motDePasseHashe){

	return password_verify($motDePasseEnClair,$motDePasseHashe);
}


<?php

require_once 'commons/init_db.php';

/**
*Cette fonction va récupérer toutes les infos d'un utilisateur à partir de son pseudo en base de données
*@param string $pseudo
*/
function getUtilisateurParSonPseudo($pseudo){
	//je récupère la cobnnexion à la bdd
	$pdo = getDb();

	// je fais une req préparée afin de se prémunir contre les req SQL

	$requeteUtilisateur = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = :pseudo");

	//j'injecte de façon sécurisée la valeur de $pseudo dans ma requete préparée
	$requeteUtilisateur->bindValue(':pseudo',$pseudo);

	//J'exécute ma requete

	$requeteUtilisateur->execute();

	// je récupère l'unique résultat de ma requete grace à la méthode fetch
	$infosUtilisateurs = $requeteUtilisateur->fetch(PDO::FETCH_ASSOC);

	//je retourne les infos de mon utlisateur
	return $infosUtilisateurs;

	//sinon autre version plus condensée en utilisant le chainage des méthodes pour l'écrire
	/*
		return getDb()->prepare("SELECT * FROM utilisateurs WHERE pseudo = :pseudo")->bindValue(':pseudo',$pseudo)->execute()->fetch();
	*/
}


/**
*Cette fonction a pour but d'insérer en bdd un nouvel utilisateur à partir d'un *tableau de données
*@param array $infosUtilisateur les infos de l'utilisateur
*/
function ajouterUtilisateur($infosUtilisateur, $avatar = 'default.png'){

		$pdo = getDb();
		
		$requetePreparee = $pdo->prepare("INSERT INTO utilisateurs (pseudo, mot_de_passe, email, avatar, sexe) VALUES (:pseudo, :mot_de_passe, :email, :avatar, :sexe)");
			//injecte moi à l'alias pseudo la valeur $_POST['pseudo'] et PDO va lui meme se protéger contre les injections SQL. On délègue donc à PDO l'injection de nos valeurs afin de se protéger contre les injections sql

			//c'e'st mieux de faire des trim à ce nivo aussi sur pseudo mot de passe et email mais pas obligatoire pour l'email car lors de la vérif on vérifie qu'il na pas d'espace non plus

			$requetePreparee->bindValue(':pseudo', trim($infosUtilisateur['pseudo']));
			$requetePreparee->bindValue(':mot_de_passe', password_hash(trim($infosUtilisateur['mot_de_passe']),PASSWORD_DEFAULT));
			$requetePreparee->bindValue(':email', $infosUtilisateur['email']);
			$requetePreparee->bindValue(':avatar', $avatar);
			$requetePreparee->bindValue(':sexe', $infosUtilisateur['sexe']);

			$requetePreparee->execute();
			return $pdo->lastInsertId(); //c'est une bonne pratique
		} 



/**
*Cette fonction renvoie vrai si il existe bien un utilisateur dont l'email ou le pseudo
*correspond à ceux passés en parametres
*@param string $email
*@param string $pseudo
*@return boolean vari si l'email ou le mot de passe correspondent à un utilisateur


*/
function emailOuPseudoExistent($email,$pseudo){
	$pdo = getDb();

	//ma requète préparée va tenter de récupérer les informations d'un utilisateur
	//dont le pseudo ou l'email seraien
	$requeteEmailPseudo = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = :email OR pseudo = :pseudo');

	$requeteEmailPseudo->bindValue(':email',$email);
	$requeteEmailPseudo->bindValue(':pseudo',$pseudo);

	$requeteEmailPseudo->execute();

	//on veut en fait obtenir un booleen donc True si on obtient bien un utilisateur qui a cet email ou ce pseudo
	return $requeteEmailPseudo->fetch() !==FALSE;
}
		
	
                                                                                  
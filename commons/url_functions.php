<?php

function getPage(){
	// je récupère l'URI courante à partir de $_SERVER, variable super globale
	$uri = $_SERVER['REQUEST_URI'];

	//$uri contient /tchat/index.php

	// j'extrait des infos sur l'URI au sein d'un tableau associatif
	$infos = pathinfo($uri);

	// Je récupère uniquement ce dont j'ai besoin dans ce tableau à savoir 'filename' qui va me donner le nom de la page

	$page = $infos['filename'];

	//Je retourne enfin le nom de la page obtenu

	return $page;
}

/**
 * cette fonction redirige l'utilisateur vers la page demandée
 *	@param string $page le nom de la page
 */
function redirectPage($page){
	header('Location:'.$page.'.php');
}
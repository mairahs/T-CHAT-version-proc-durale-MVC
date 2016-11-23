<?php 
//Début de mon "sous " controlleur

require_once 'models/salons.php';

$nomsSalon = getNomsSalons();
//var_dump($nomsSalon);


?>


<h3><a href="index.php" title="Revenir à l'accueil">Les salons</a></h3>
			
			<nav>
					<ul class="menu-salons">
						<!-- on affiche ici le nom de chacun des salons grâce à un foreach -->
						<?php foreach ($nomsSalon as $nomSalon): ?>
							<!-- <li><a href="#salon-<?php echo $id; ?>"> -->
							<li><a href="salon.php?id=<?php echo $nomSalon['id']; ?>"><?php echo $nomSalon['nom']; ?></a></li>
						<?php endforeach; ?>
					</ul>
					<a href="deconnexion.php" title="Se déconnecter de T-Chat">Déconnexion</a>
				</nav>
			
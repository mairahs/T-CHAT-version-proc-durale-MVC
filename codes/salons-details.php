
<ul class="mes-salons">
				<!-- on parcourre à nouveau le nom de chacun des salons -->
				<?php foreach ($nomsSalon as $id => $nomSalon): ?>
					<li id="salon-<?php echo $id; ?>">
						<!-- rappel du nom du salon -->
						<?php echo $nomSalon; ?> :
						<ul>
							<!-- on parcourre maintenant les messages dans un 
							foreach imbriqué -->
							<?php foreach ($salons[$nomSalon] as $personne => $message): ?>
								<li><?php echo $personne; ?> : "<?php echo $message; ?>"</li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endforeach; ?>
			</ul>
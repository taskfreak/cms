<h3 class="acctog">Import de contacts</h3>
<div class="accinf">
	<?php
		if (is_array($_SESSION['arrContactImport'])) {
	?>
		<div class="info">
			Confirmer l'importation dans la liste de diffusion
		</div>
		<p style="text-align:center">
			<input type="submit" name="csvconfirm" class="bsave" value="Oui, ajouter les <?php echo count($_SESSION['arrContactImport']); ?> contacts" />
			&nbsp; &nbsp;
			<input type="submit" name="csvcancel" class="bclose" style="display:inline" value="Non, annuler" />
		</p>
		<table class="form_grid">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Pr&eacute;nom</th>
					<th>Email</th>
					<th>Adresse</th>
					<th>Code postal</th>
					<th>Ville</th>
				</tr>
			</thead>
			<tbody>
	<?php
			foreach($_SESSION['arrContactImport'] as $objContact) {
				echo '<tr>';
				echo '<td>'.$objContact->get('lastName').'</td>';
				echo '<td>'.$objContact->get('firstName').'</td>';
				echo '<td>'.$objContact->get('email').'</td>';
				echo '<td>'.$objContact->get('address').'</td>';
				echo '<td>'.$objContact->get('zipCode').'</td>';
				echo '<td>'.$objContact->get('city').'</td>';
				echo '</tr>';
			}
	?>
			</tbody>
		</table>
	<?php
		} else {
	?>
		<div class="info">
			Importez vos contacts dans la liste de diffusion
		</div>
		<div style="margin: 0 10% 0 10%">
			<p>Veuillez selectionner un fichier au format CSV.</p>
			<p>Pour g&eacute;n&eacute;rer un fichier CSV depuis MS Excel:</p>
			<ul>
				<li>Menu Fichier &gt; Enregistrer sous...</li>
				<li>Selectionner le format CSV</li>
				<li>Verifier que les champs sont bien s&eacute;par&eacute;s par des points-virgules ;</li>
			</ul>
			<p>
				<input type="file" name="csvfile" value="" />
				<button type="submit" name="csvimport" value="1">Importer</button>
				<br /><small>fichiers accept&eacute;s: .csv (max: <?php echo $pMaxSize; ?>)</small>
			</p>
		</div>
	<?php
		}
	?>
</div>
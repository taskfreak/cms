<div class="set_section">
	Newsletters
</div>
<div id="mailing_page_list">
	<table class="form_top form_hover form_click" width="100%">
		<thead>
			<tr>
				<th width="20%">Page</th>
				<th width="50%">Titre derni&egrave;re lettre</th>
				<th width="15%">Date de r&eacute;daction</th>
				<th width="15%">Date d'envoi</th>
			</tr>
		</thead>
		<tbody>
<?php
	while ($objItem = $objContentList->rNext()) {
		$rowstyle = ($r++ % 2)?' class="odd"':'';
?>
            <tr<?php echo $rowstyle; ?> onclick="window.location.href='admin_page_content.php?id=<?php echo $objItem->id; ?>'">
				<td><a href="admin_page_content.php?id=<?php echo $objItem->id; ?>"><?php echo $objItem->get('menu'); ?></a></td>
				<td><?php
				if ($id = intval($objItem->newsletter->id)) {
					echo '<a href="admin_page_content.php?id='.$objItem->id
						.'&amp;item='.$id.'">'
						.$objItem->newsletter->get('title',100).'</a>';
				} else {
					echo '-';
				}
				?></td>
				<td><?php 
					if (intval($objItem->newsletter->id)) {
						echo $objItem->newsletter->getDtm('creationDate','SHT'); 
					} else {
						echo '- aucune -';
					}
				?></td>
				<td><?php echo $objItem->newsletter->getDeliveryDate(); ?></td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
</div>

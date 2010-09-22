<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 0.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

echo '<input type="hidden" name="item" value="'.$this->content->id.'" />';

if ($this->content->isLoaded()) {
	echo '<input type="hidden" name="items2delete" value="" />';
	?>
	<table class="form_info">
		<thead>
			<tr>
				<th>Date Cr&eacute;ation</th>
				<th>Derni&eacute;re modification</th>
				<th>Dernier envoi</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php $this->content->_join->p('creationDate','SHT'); ?></td>
				<td><?php $this->content->p('lastChangeDate','SHT'); ?></td>
				<td><?php $this->content->_join->p('deliveryDate','SHT'); ?></td>
			</tr>
		</tbody>
	</table>
	<?php
}

?>
	<p>
		<label>Titre :</label>
		<?php $this->content->_join->qText('title','','wxl'); ?>
	</p>
	
	<?php
		$this->content->qEditArea();
	?>

	<h4>Articles et Programmes li&eacute;s:</h4>
	<ol class="fields">
		<li>
			<?php
				$this->content->viewModuleSelect();
			?>
		</li><li>
			<?php
				$this->content->viewModuleList();
			?>
		</li>
	</ol>

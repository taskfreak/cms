<h3 class="acctog">Options</h3>
<div class="accinf">
	<p>
		Page de d&eacute;part pour la recherche :<br />
		<?php 
		// $this->objPageList->qSelect2('root_page', 'position', 'getOutlineTitle()', $this->content->getOption('root_page'));
		$this->objPageList->qSelect('option_root_page','title',$this->content->getOption('root_page')); 
		?>
	</p>
	<p>
		Longueur de l'extrait &agrave; afficher dans les r&eacute;sulats :<br />
		<input type="text" name="option_result_length" class="wxs" value="<?php
			if ($this->content->getOption('result_length')) {
				echo $this->content->getOption('result_length');
			} else {
				echo '200';
			}
		?>" />
	</p>
</div>
<h3 class="acctog">Introduction</h3>
<div class="accinf">
	<?php
		$this->content->qEditArea();
	?>
</div>
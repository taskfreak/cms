<?php

for($i=0; $i<$this->c; $i++ ) {

	$this->content[$i]->qEditArea('body'.$i);
	
	echo '<hr style="height:0px; padding:0; border: 1px dotted #ccc; margin:8px 0" />';

}

// option (number of areas)

?>
<p>
	<label>Nombre de champs texte :</label>
	<input type="text" name="option_multi_count" class="wxs" value="<?php
		if ($this->content[0]->getOption('multi_count')) {
			echo $this->content[0]->getOption('multi_count');
		} else {
			echo '2';
		}
	?>" />
</p>
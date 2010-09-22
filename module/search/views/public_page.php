<?php
$this->content->printContent();


if ($this->results->rMore()) {

	echo "<h4>R&eacute;sultats de la recherche sur &laquo; ".$this->keyword." &raquo;</h4>";

	echo '<ul>';
	
	$length = $this->content->getOption('result_length');
	if (!intval($length)) {
		$length = 200;
	}
	while ($objItem = $this->results->rNext()) {
	
		$strExtract = strip_tags($objItem->body);
	
		$idx = strpos($strExtract, $this->words[0]);
		if ($idx > 50) {
			$strExtract = '... '.substr($strExtract, $idx-50, $length).' ...';
		} else if (strlen($strExtract) > $length) {
			$strExtract = substr($strExtract, 0, $length).' ...';
		}
		
		
        foreach($this->words as $word) { 
			if(strlen(trim($word)) != 0) {
				$strExtract = str_ireplace($word, "<strong>".$word."</strong>", $strExtract);
			}
		}
			
		echo '<li><a href="'.$objItem->page->getUrl().'">'
			.$objItem->page->get('title').'</a><br />'
			.'<small>'.$strExtract.'</small>'
			.'</li>';
	
	}
	echo '</ul>';

} else {
	echo "<p>Votre recherche sur &laquo; ".$this->keyword." &raquo; n'a retourn&eacute;e aucun r&eacute;sultat</p>";
}
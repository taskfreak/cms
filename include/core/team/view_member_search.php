<?php

if (count($this->arrMembersAdded)) {
	echo '<script type="text/javascript">'."\n";
	foreach($this->arrMembersAdded as $objItem) {
		$html = '<div class="col c50">'. $objItem->member->getName().'</div>'
			.'<div class="col c30" id="mbp_'.$objItem->member->id.'">'.$objItem->getPosition().'</div>'
			.'<div class="col c20 action">'
			.'<a href="javascript:ajaxify_request(\''.CMS_WWW_URI.'admin/team.php?id='.$this->team->id.'&amp;mode=member_remove'
			.'&amp;member='.$objItem->member->id.'\',\'\')">'.$GLOBALS['langAdmin']['remove'].'</a>'
			// .'<a href="javascript:cms_team_position('.$objItem->member->id.','.$this->team->id.')">'.$GLOBALS['langAdmin']['edit'].'</a>'
			.'</div>';
		echo "var el = new Element('div',{'id':'mbt_".$objItem->member->id."','class':'row'});\n";
		echo "el.set('html','".str_replace("'","\\'",$html)."');\n";
		echo "el.inject('team_member_list','bottom');\n";
	}
	echo '</script>';
}

if ($this->memberSearchList->rMore()) {
	echo '<p>Choisir la position et cliquez sur le nom du membre &agrave; ajouter : ';
	$this->positionList->qSelect('position');
	echo '<ul>';
	while ($objItem = $this->memberSearchList->rNext()) {
		echo '<li>';
		if (array_key_exists($objItem->id, $this->arrMembers)) {
			echo $objItem->getName().' <small>('.$this->arrMembers[$objItem->id].')</small>';
		} else {
			echo '<input id="cm_'.$objItem->id.'" type="checkbox" name="member[]" value="'.$objItem->id.'">'
				.'<label for="cm_'.$objItem->id.'">'.$objItem->getName().'</label>';
		}
		
		echo '</li>';
	}
	echo '</ul>';
	echo '<p class="ctr">';
	$GLOBALS['objCms']->adminSubmitButtons();
	echo '</p>';
} else {
	echo $GLOBALS['langTznCommon']['search_empty'];
}

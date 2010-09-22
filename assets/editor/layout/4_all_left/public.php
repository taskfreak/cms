<?php

include CMS_WWW_PATH.'assets/editor/php/public_parse.php';

if (count($arrImg) || count($arrDoc)) {
?>
<div style="float:left;">
<?php

	// --- PHOTO LIST ---
	if (count($arrImg)) {
		echo '<div class="imglist">';
		foreach($arrImg as $objItem) {
			echo '<div style="padding: 0px 10px 10px 0px"><img src="'. $objItem->getImgUrl('filename','',2).'" border="0" alt="'.$objItem->get('title').'" /></div>';
		}
		echo '</div>';
	}
	
	// --- DOC LIST ----
	if (count($arrDoc)) {
		echo '<div class="doclist"><ul>';
		foreach($arrDoc as $objItem) {
			echo '<li><a href="'.$objItem->getUrl('filename').'">'.$objItem->getInfo(15).'</a></li>';
		}
		echo '</ul></div>';
	}

?>
</div>
<?php
}
?>
<div>
<?php

// --- CONTENT ---
$objContent->p('body');

?>
</div>
<?php

include CMS_WWW_PATH.'assets/editor/php/public_parse.php';

if (count($arrImg)) {
	$objItem = array_shift($arrImg);
	echo '<p align="center"><img src="'. $objItem->getImgUrl('filename','',1).'" border="0" alt="'.$objItem->get('title').'" class="blk"/></p>';
}
$objContent->p('body');

if (count($arrImg)) {
	echo '<div class="imglist">';
	foreach($arrImg as $objItem) {
		echo '<div style="float:left; padding: 10px 10px 10px 0px"><img src="'. $objItem->getImgUrl('filename','',2).'" border="0" alt="'.$objItem->get('title').'" /></div>';
	}
	echo '</div>';
}

if (count($arrDoc)) {
	echo '<div class="doclist"';
	if (count($arrImg)) {
		echo ' style="clear:left;"'; 
	}
	echo '><ul>';
	foreach($arrDoc as $objItem) {
		echo '<li><a href="'.$objItem->getUrl('filename').'">'.$objItem->getInfo().'</a></li>';
	}
	echo '</ul></div>';
}

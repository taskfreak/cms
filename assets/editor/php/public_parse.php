<?php
// remove doclist, imglist and img [] tags
$objContent->body = preg_replace('(\[(doclist|imglist|img:\d(:\w+)?)\])','',$objContent->body);

// removing already displayed images from the list
if (preg_match_all('/<img[^>]+id=\"img-([0-9]+)\"[^>]*>/',$objContent->body,$arrResp)) {
	foreach($arrResp[1] as $pair) {
		unset($arrImg[$pair-1]);
	}
}

// single doc
 
/*
function fillDocu($arrResp)
{
	$str = '';
	$idx = $arrResp[1];
	if ($objItem = $arrDocu[$idx]) {
		$str = '<a href="'.$objItem->getUrl('filename').'">'.$objItem->getInfo().'</a>';
	}
	return $str;
}

if (preg_match('/\[doc:([0-9]+)\]/',$objContent->body,$arrResp)) {
	$objContent->body = preg_replace_callback(
		"|\[doc:([0-9]+)\]|",
		"fillDocu",
		$objContent->body);
}
*/
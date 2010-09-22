<?php
$body = $objContent->body;

class ContentAssets
{
	var $arrImg;
	var $arrDoc;
	
	function ContentAssets($arrImg, $arrDoc) {
		$this->arrImg = $arrImg;
		$this->arrDoc = $arrDoc;
	}
	
	function fillPhoto($arrResp)
	{
		$str = '';
		$idx = $arrResp[1] - 1;
		// error_log("replacing img $img: ".$this->arrImg[$idx]);
		if ($objItem = $this->arrImg[$idx]) {
			switch ($arrResp[2]) {
				case ':left':
					$str = '<img src="'. $objItem->getImgUrl('filename','',2).'" border="0" alt="'.$objItem->get('title').'" class="lft"/>';
					break;
				case ':right':
					$str = '<img src="'. $objItem->getImgUrl('filename','',2).'" border="0" alt="'.$objItem->get('title').'" class="rgt"/>';
					break;
				default:
					$str = '<img src="'. $objItem->getImgUrl('filename','',2).'" border="0" alt="'.$objItem->get('title').'" />';
					break;
			}
			unset($this->arrImg[$idx]);
		}
		return $str;
	}
	
	function fillDocu($arrResp)
	{
		$str = '';
		$idx = $arrResp[1] - 1;
		if ($objItem = $this->arrDoc[$idx]) {
			$str = '<a href="'.$objItem->getUrl('filename').'">'.$objItem->getInfo().'</a>';
			unset($arrDoc[$idx]);
		}
		return $str;
	}
}

$objAssets = new ContentAssets($arrImg,$arrDoc);

// images
if (preg_match('/\[img:([0-9]+)(:[a-z]+)?\]/',$body,$arrResp)) {
	$body = preg_replace_callback(
		"|\[img:([0-9]+)(:[a-z]+)?\]|",
		array(&$objAssets,"fillPhoto"),
		$body);
}

if (preg_match_all('/<img[^>]+id=\"img-([0-9]+)\"[^>]*>/',$body,$arrResp)) {
	foreach($arrResp[1] as $pair) {
		unset($objAssets->arrImg[$pair-1]);
	}
}

// single doc

if (preg_match('/\[doc:([0-9]+)\]/',$body,$arrResp)) {
	$body = preg_replace_callback(
		"|\[doc:([0-9]+)\]|",
		array(&$objAssets,"fillDocu"),
		$body);
}

// photo list

if (strpos($body,'[imglist]') !== false) {
	if (count($objAssets->arrImg)) {
		$str = '<div class="imglist"><ul>';
		foreach($objAssets->arrImg as $objItem) {
			$str .= '<div style="padding: 0px 0px 10px 10px"><img src="'. $objItem->getImgUrl('filename','',2).'" border="0" alt="'.$objItem->get('title').'" /></div>';
		}
		$str .= '</ul></div>';
	}
	
	$body = preg_replace('(\[imglist\])',$str,$body);
	
	unset ($objAssets->arrImg);

}

// doc list

if (strpos($body,'[doclist]') !== false) {
	if (count($objAssets->arrDoc)) {
		$str = '<div class="doclist"><ul>';
		foreach($objAssets->arrDoc as $objItem) {
			$str .= '<li><a href="'.$objItem->getUrl('filename').'">'.$objItem->getInfo(15).'</a></li>';
		}
		$str .= '</ul></div>';
	}
	
	$body = preg_replace('(\[doclist\])',$str,$body);
	
	unset($objAssets->arrDoc);

}

echo $body;

// remaining photos

if (count($objAssets->arrImg)) {
	echo '<div class="imglist">';
	foreach($objAssets->arrImg as $objItem) {
		echo '<div style="float:left; padding: 10px 10px 10px 0px"><img src="'. $objItem->getImgUrl('filename','',2).'" border="0" alt="'.$objItem->get('title').'" /></div>';
	}
	echo '</div>';
}

// remaining documents

if (count($objAssets->arrDoc)) {
	echo '<div class="doculist" style="clear:left;"><ul>';
	foreach($objAssets->arrDoc as $objItem) {
		echo '<li><a href="'.$objItem->getUrl('filename').'">'.$objItem->getInfo().'</a></li>';
	}
	echo '</ul></div>';
}

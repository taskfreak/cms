<?php
include '../../config.php';
include CMS_CORE_PATH.'twitter/core.php';

$format  = 'j M Y, G:i:s';

$obj = new TwitterRss();
if ($obj->saveCache()) {
	echo date($format).': RSS loaded, cache saved';
} else {
	echo date($format).': failed loading RSS';
}
echo "\n";
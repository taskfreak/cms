<?php
include '_include.php';

// echo($fileName);
$fileName = stripslashes(urldecode($_GET["fileName"]));
if (($_GET["mode"] == "real") || ($_GET["mode"] == "home")) {
	$fileName = $fileName;
} else {
	$fileName = $_GET["mode"].$fileName;
}
$fileName = urlencode($fileName);

if ($_REQUEST["q"]) {
    $q = $_REQUEST["q"];
} else {
    $q = TZN_FILE_GD_QUALITY;
}

//error_log('getThumb: '.$fileName.' at '.$_GET["newxsize"].' x '.$_GET["newysize"]);

$obj = new TznThumbnail($fileName, $_GET["newxsize"], $_GET["newysize"], false, false, false,$q);
echo $obj->generate();


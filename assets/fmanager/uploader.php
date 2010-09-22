<?php

include './core.php';

// sleep(3);

$func = intval($_REQUEST['CKEditorFuncNum']);

$objFile = new CmsEditorFile($_REQUEST['Type']);

$str = '<script type="text/javascript">';

if ($objFile->upload()) {
	$objFile->loadList();
	$html = str_replace("'","\\'",$objFile->getListHtml($func));
	$str .= "window.parent.ck_upload('$html');";
} else {
	$str .= 'window.parent.ck_error("'.$objFile->_error.'")';
}
$str .= '</script>';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Uploaded</title></head>
<body><?php echo $str; ?></body>
</html>

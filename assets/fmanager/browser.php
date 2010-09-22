<?php

include './core.php';

/*

Type :: images
CKEditor :: i_body
CKEditorFuncNum :: 1
langCode :: en
CKFinder_Path :: Images:/:1
PHPSESSID :: xxxxxxx

*/

$func = intval($_REQUEST['CKEditorFuncNum']);

$objFileList = new CmsEditorFile($_REQUEST['Type']);
$objFileList->loadList();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>File Browser</title>
	<link rel="stylesheet" type="text/css" href="<?php echo CMS_WWW_URI; ?>assets/fmanager/browser.css" />
	<script type="text/javascript" src="<?php echo CMS_WWW_URI; ?>assets/js/mootools-1.2.4.js"></script>
	<script type="text/javascript" src="<?php echo CMS_WWW_URI; ?>assets/fmanager/browser.js"></script>
</head>
<body>
<div id="global">
	<div id="header">
		<h1>File Manager</h1>
		<h4><?php echo $objFileList->_base; ?></h4>
	</div>
	<div id="flist">
	<?php
		echo $objFileList->getListHtml($func);
	?>
	</div>
	<iframe name="upload_iframe" style="width: 400px; height: 1px; border: 0px;"></iframe>
</div>
<form id="footer" enctype="multipart/form-data" method="post" action="<?php echo CMS_WWW_URI.'assets/fmanager/uploader.php'; ?>"
	target="upload_iframe" onsubmit="$('footer').addClass('loading')">
	Upload another file :
	<input type="hidden" name="CKEditorFuncNum" value="<?php echo $func; ?>" />
	<input type="hidden" name="Type" value="<?php echo $_REQUEST['Type']; ?>" />
	<input type="file" id="i_myfile" name="myfile" value="" onchange="$('footer').addClass('loading');this.form.submit()" />
	<button type="submit" name="upload" value="1">Upload</button>
</form>
</body>
</html>
<?php
include '../../_include.php';

TznCms::init(1);
if (!constant('CMS_SECURITY')) {
	echo 'Security Error : user not logged in';
	error_log('Security Error : user not logged in');
	exit;
}

$objModule = $GLOBALS['objCms']->getModuleObject('taskfreak');

$error = 'Erreur : verifier taille fichier max ('.CMS_UPLOAD_MAX_SIZE.')'; // default error message

if (!isset($_FILES['newfile'])) {

	// error_log('file post empty');

} else if (empty($_FILES['newfile']['size'])) {

	$error = 'Veuillez selectionner un fichier a ajouter';
	// error_log('file size 0');

} else {

	// uploading file
	
	$isImg = TznFile::isImage($_FILES['newfile']['type']);
	
	// error_log('uploading '.(($isImg)?'image':'doc').' '.$_FILES['newfile']['name']);
	
	$objFile = new TznFile(($isImg)?'image':'document');
	
	if ($objFile->upload('newfile')) {
		// error_log('uploaded as '.$objFile->tempName);
		$error = '';
	} else {
		$error = $objFile->_error['newfile'];
	}

}
?>
<html>
<head>
</head>
<body>
<script language="javascript" type="text/javascript">
<?php
if ($error) {
	// file not uploaded
	echo 'alert("'.str_replace('\\\'','\'',$error).'"); ';
	echo 'window.parent.tf_upload_clean();';
} else {
?>
window.parent.tf_uploaded('<?php echo $objFile->tempName; ?>','<?php echo $objFile->origName; ?>','<?php echo $objFile->fileType; ?>','<?php echo $objFile->getShortSize(); ?>');
<?php	
}
?>
</script>
</body>
</html>
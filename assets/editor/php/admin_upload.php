<?php

// error_log('uploading '.$pFileType.': '.$_FILES[$pFileType.'new']['name']);

$varname = $pFileType.'new';
$objFile = new TznFile(($pFileType == 'doc')?'document':'image');
$objFile->upload($varname);

// error_log('uploaded as '.$objFile->tempName);
?>
<html>
<head>
</head>
<body>
<script language="javascript" type="text/javascript">
<?php
if ($objFile->tempName) {
?>
window.parent.addFile('<?php echo $pFileType; ?>','<?php echo $objFile->tempName; ?>','<?php echo $objFile->origName; ?>','<?php echo $objFile->fileType; ?>','<?php echo $objFile->getShortSize(); ?>');
<?php	
} else {
	
	// file not uploaded
	$pError = ($objFile->_error[$varname])?$objFile->_error[$varname]:
		('Impossible de charger le fichier\\ntaille de fichier: '
		.$pMaxSize.' max');
	echo 'alert("'.$pError.'");';
	echo 'window.parent.rstFile("'.$pFileType.'");';
}
?>
</script>
</body>
</html>
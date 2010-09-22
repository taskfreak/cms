<?php

include CMS_INCLUDE_PATH.'language/'.CMS_LANGUAGE.'/editor.php';

// check max file size
$pMaxSize = ini_get('upload_max_filesize');
if (intval(ini_get('post_max_size')) < intval($pMaxSize)) {
	$pMaxSize = ini_get('post_max_size');
}

?>
<div id="tzneditor">
	<ul class="tzntabs">
		<li><a id="tzneditor_lnk_text" href="javascript:cms_switch_panel('tzneditor','text');" class="current"><?php echo $GLOBALS['langAdminEditor']['tab_text']; ?></a></li>
		<li><a id="tzneditor_lnk_img" href="javascript:cms_switch_panel('tzneditor','img');"><?php echo $GLOBALS['langAdminEditor']['tab_images']; ?></a></li>
		<li><a id="tzneditor_lnk_doc" href="javascript:cms_switch_panel('tzneditor','doc');"><?php echo $GLOBALS['langAdminEditor']['tab_documents']; ?></a></li>
		<li><a id="tzneditor_lnk_layout" href="javascript:cms_switch_panel('tzneditor','layout');"><?php echo $GLOBALS['langAdminEditor']['tab_layout']; ?></a></li>
	</ul>
<?php

/* --- TEXT --------------------------------------------------------- */

?>
	<div id="tzneditor_pan_text" class="tznpanel">
		<?php
		$this->qTextarea($name,'','','style="width:99%;height:'
			.(($height)?$height:'350').'px"');
		echo '<script type="text/javascript">CKEDITOR.replace(\''.$name.'\',{toolbar:\''.$this->_mode.'\'});</script>';
		?>
	</div>
<?php

/* --- PHOTOS ------------------------------------------------------- */

?>
	<div id="tzneditor_pan_img" class="tznpanel tznframe hide">
		<div id="imglist">
		<?php
		while ($objFile = $this->_imgList->rNext()) {
		?>
		<div id="img_<?php echo $objFile->id; ?>" class="row">
			<a href="javascript:delFile('img','<?php echo $objFile->id; ?>',true)" class="del">X</a>
			<?php echo $objFile->getInfo(); ?>
		</div>
		<?php
		}
		?>
		</div>
		<div id="imgconfirm" style="display:none"><?php echo $GLOBALS['langAdminEditor']['help_upload']; ?></div>
		<div id="imgform">
           <span id="imgfield"><input type="file" name="imgnew" value="" onchange="turnUplOn('img')" /></span>
           <input type="hidden" name="imgdel" value="" />
           <button type="button" id="imgadd" name="imgadd" value="1" onclick="turnUplOn('img')"><?php echo $GLOBALS['langAdminEditor']['add_image']; ?></button>
           <small><?php echo $GLOBALS['langAdminEditor']['help_files']; ?> .png, .jpg, .gif (max: <?php echo $pMaxSize; ?>)</small>
		</div>
	</div>
<?php

/* --- DOCS ---------------------------------------------------------- */

?>
	<div id="tzneditor_pan_doc" class="tznpanel tznframe hide">
		<div id="doclist">
		<?php
		while ($objFile = $this->_docList->rNext()) {
		?>
		<div id="doc_<?php echo $objFile->id; ?>" class="row">
			<a href="javascript:delFile('doc','<?php echo $objFile->id; ?>',true)" class="del">X</a>
			<?php echo $objFile->getInfo(); ?>
		</div>
		<?php
		}
		?>
		</div>
		<div id="docform">
           <span id="docfield"><input type="file" name="docnew" value="" onchange="turnUplOn('doc')" /></span>
           <input type="hidden" name="docdel" value="" />
           <button type="button" id="docadd" name="docadd" value="1" onclick="turnUplOn('doc')">Ajouter</button>
           <small><?php echo $GLOBALS['langAdminEditor']['help_files']; ?> .pdf, .doc, .zip (max: <?php echo $pMaxSize; ?>)</small>
		</div>
	</div>
<?php

/* --- LAYOUT -------------------------------------------------------- */

?>
	<div id="tzneditor_pan_layout" class="tznpanel tznframe hide">
		<?php 
		$default = $this->getOption('layout');
		if (!$default) {
			$defaultIsFirst = true;
		}
		$i = 0;
		foreach ($this->_layoutList->_data as $key => $value) {
			if ($i > 0 && ($i % 3 == 0)) {
				echo '<div style="clear:left"></div>';
			}
			echo '<div class="layout">';
			echo '<input id="lay'.$i.'" type="radio" name="option_layout" value="'.$key.'"';
			if ($key == $default || $defaultIsFirst) {
				echo ' checked="checked"';
				$defaultIsFirst = false;
			}
			echo ' />';
			echo '<label for="lay'.$i.'"><img src="'.CMS_WWW_URI.'assets/editor/layout/'.$this->_layoutList->getImg($key).'" title="'.$value.'" /></label>';
			echo '</div>';
			$i++;
		}	
		?>
		<div style="clear:left"></div>
	</div>
</div>
<?php

/* --- IMAGE PLACEMENT POPUP ----------------------------------------- */

?>
<div id="tzneditor_pop_img">
	<table>
		<tr>
			<th><?php echo $GLOBALS['langAdminEditor']['help_pop_img']; ?>:</th><td><select id="edt_img_sel" name="edt_img_sel"><option value="1">1</option></select></td>
		</tr>
		<tr>
			<th><?php echo $GLOBALS['langAdminEditor']['help_pop_pos']; ?>:</th><td><select id="edt_img_alg" name="edt_img_alg"><option value=""><?php echo $GLOBALS['langAdminEditor']['help_pop_pos_auto']; ?></option><option value="lft"><?php echo $GLOBALS['langAdminEditor']['help_pop_pos_left']; ?></option><option value="rgt"><?php echo $GLOBALS['langAdminEditor']['help_pop_pos_right']; ?></option></select></td>
		</tr>
		<tr>
			<th><?php echo $GLOBALS['langAdminEditor']['help_pop_alt']; ?>:</th><td><input type="text" id="edt_img_alt" name="edt_img_alt" value="" /></td>
		</tr>
		<tr>
			<th><input type="checkbox" id="edt_img_fll" name="edt_img_fll" value="1" /></th><td><label for="edt_img_fll"><?php echo $GLOBALS['langAdminEditor']['help_pop_original']; ?></label></td>
		</tr>
		<tr>
			<th>&nbsp;</th><td><button type="button" id="edt_pop_img_ok" onclick="widgImgAdd();return false;"><?php echo $GLOBALS['langAdminEditor']['help_pop_add']; ?></button>&nbsp;<button id="edt_pop_img_cx" onclick="widgImgClose();return false;"><?php echo $GLOBALS['langAdminEditor']['help_pop_cancel']; ?></button></td>
		</tr>
	</table>
</div>
<input type="hidden" id="upload_mode" name="uploadmode" value="" />
<iframe name="upload_iframe" style="width: 400px; height: 1px; border: 0px;"></iframe>
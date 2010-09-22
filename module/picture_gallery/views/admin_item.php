	<ol class="fields">
		<li>
			<label>Date :</label>
			<?php $this->content->_join->qDateSelect('cms_date','postDate','NOW'); ?>
		</li>
		<?php
		 // multiple page choice
		 if (!$GLOBALS['objPage']->id) {
		?>
			<li>
				<label>Page :</label>
				<?php $this->objPageList->qSelect('pageId','title',$this->content->pageId); ?>
			</li>
		<?php
		 }
		?>
		<li>
			<label>Titre :</label>
			<?php $this->content->_join->qText('title','','wxl'); ?>
		</li>
		<li>
			<label>Image :</label>
			<?php $this->content->_join->qImage('imgfile'); ?>
		</li>
		<?php
		if (!$GLOBALS['confModule']['picture_gallery']['fck_editor']) {
		?>
		<li>
			<label>Description :</label>
			<?php $this->content->qTextArea('body','','wxl hm'); ?>
		</li>
		<?php
		}
		?>
	</ol>
	<hr class="sep" />
	<?php
	
	if ($GLOBALS['confModule']['picture_gallery']['fck_editor']) {
		$oFCKeditor = new FCKeditor('body');
		$oFCKeditor->BasePath = CMS_WWW_URI.'assets/fckeditor/';
		$oFCKeditor->ToolbarSet = 'Default';
		$oFCKeditor->Width  = '99%';
		$oFCKeditor->Height = '300px';
	
		$oFCKeditor->Value = $this->content->body;
	
		$oFCKeditor->Create() ;

	}
	
	echo '<input type="hidden" name="item" value="'.$this->content->_join->id.'" />';
	
	//if ($this->content->_join->isLoaded()) {
		//Tzn::qHidden('item',$this->content->_join->id);
	//}
	//Tzn::qHidden('handle','blog');
	?>
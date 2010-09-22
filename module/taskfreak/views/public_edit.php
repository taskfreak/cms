<div id="tf_head">
	<?php
	/*
	if (trim($this->intro->body)) {
		$this->intro->printContent();
	}
	*/
	$this->req['show'] = '';
	include $this->filePath('include/head.php');
	?>
</div>
<div id="tf_nav">
	<?php
	if ($GLOBALS['objUser']->hasAccess(11,'taskfreak')) {
		echo '<ul><li><a href="'.$this->getUrl('edit').'" accesskey="n">Cr&eacute;er une t&acirc;che</a></li></ul>';
	}
	echo '<ul class="lst"><li><a href="'.$this->getUrl('list').'" accesskey="b">Retour &agrave; la liste</a></li></ul>';
	?>
</div>
<div id="tf_main">
	<?php
	
	/* --- Task Form ---------------------------------------------------------- */
	
	if (@constant('CMS_REWRITE_URL')) {
		echo '<form id="tf_form" action="'.$GLOBALS['objPage']->getUrl().'" method="post" enctype="multipart/form-data">';
	} else {
		echo '<form id="tf_form" action="'.CMS_WWW_URI.'" method="post" enctype="multipart/form-data">';
		echo '<input type="hidden" name="page" value="'.$GLOBALS['objPage']->id.'" />';
	}
	echo '<input type="hidden" name="action" value="edit" />';
	echo '<input type="hidden" name="item" value="'.$this->task->id.'" />';
	
	?>
	<fieldset class="compact">
		<legend><?php
		if ($this->task->isLoaded()) {
			echo 'Modification de la t&acirc;che';
		} else {
			echo 'Creation nouvelle t&acirc;che';
		}
		?></legend>
		<ol class="fields multicol side">
			<li class="nline styled compulsory">
				<label><?php echo $GLOBALS['langTaskForm']['title']; ?> :</label>
				<?php $this->task->qText('title','','wxl'); ?>
			</li>
			<li class="nline compulsory">
				<label><?php echo $GLOBALS['langTaskForm']['project']; ?> :</label>
				<?php $this->projects->qSelect('project','name',$this->task->project->id,'','width:200px','onchange="tf_project_users(this)"'); ?>
			</li>
			<li class="inline">
				<span>
					<input id="visi0" type="radio" name="showPrivate" value="0" <?php if ($this->task->showPrivate == 0) echo 'checked="checked" '; ?>/>
					<label for="visi0"><?php echo $GLOBALS['langTaskForm']['public']; ?></label>
				</span>
				<?php
                if ($GLOBALS['objUser']->hasAccess(12, 'taskfreak')) { 
		        ?>
		        <span>
              		<input id="visi1" type="radio" name="showPrivate" value="1" <?php if ($this->task->showPrivate == 1) echo 'checked="checked" '; ?>/>
              		<label for="visi1"><?php echo $GLOBALS['langTaskForm']['internal']; ?></label>
              	</span>
          		<?php
                } else if ($this->task->showPrivate == 1) {
                    $this->task->showPrivate++;
                }
          		?>
          		<span>
              		<input id="visi2" type="radio" name="showPrivate" value="2" <?php if ($this->task->showPrivate == 2) echo 'checked="checked" '; ?>/>
              		<label for="visi2"><?php echo $GLOBALS['langTaskForm']['private']; ?></label>
              	</span>
			</li>
			<?php
			if (@constant('FRK_CONTEXT_ENABLE')) {
			?>
			<li class="nline">
				<label><?php echo $GLOBALS['langForm']['context']; ?> :</label>
				<?php $this->contexts->qSelect('context',$this->task->context,'','width:100px'); ?>
			</li>
			<?php
			}
			?>
		</ol>
		<ol class="fields multicol top second">
			<li class="nline">
				<label><?php echo $GLOBALS['langTaskForm']['priority']; ?></label>
				<?php
					$objTemp = new ItemPriority();
	                $objTemp->qSelect('priority',($this->task->priority)?$this->task->priority:FRK_PRIORITY_DEFAULT,'','wxs');
                ?>
			</li>
			<li>
				<label><?php echo $GLOBALS['langTaskForm']['deadline']; ?></label>
				<?php $this->task->qDateSelect('cms_date','deadlineDate',''); ?>
			</li>
			<li>
				<label><?php echo $GLOBALS['langTaskForm']['user']; ?></label>
				<?php
				$this->users->qSelect2('user','getMemberId()','getMemberName()','','-','','style="width:150px"');
				?>
			</li>
			<li>
				<label><?php echo $GLOBALS['langTaskForm']['status']; ?>:</label>
				<?php
				$this->task->qSelectStatus('status','style="width:150px"');
				?>
			</li>
		</ol>
	</fieldset>
	<?php if (@constant('FRK_DESCRIPTION_ENABLE')) { ?>
	<fieldset>
		<legend>Description</legend>
		<ol class="fields side">
			<li>
				<label><?php echo $GLOBALS['langTaskForm']['description']; ?> :</label>
				<textarea name="description" class="wxl hl"><?php echo $this->task->description; ?></textarea>
			</li>
			<li>
				<label>Fichiers attach&eacute;s :</label>
    	  		<input type="file" id="i_newfile" name="newfile" value="" onchange="tf_upload()" />
    	  		<button type="button" id="fileadd" onclick="tf_upload()" title="Formats acc&eacute;pt&eacute;s<br /><small>.pdf, .doc, .zip, .jpg, .png, .gif<br />taille max: <?php 
    	  			echo CMS_UPLOAD_MAX_SIZE; ?></small>">Ajouter</button>
    	  		<div id="tf_attachs">
    	  		<?php
    	  		if ($this->files) {
					if ($this->files->rMore()) {
						echo '<input type="hidden" id="i_files2del" name="files2del" value="" />';
						while ($objFile = $this->files->rNext()) {
							echo '<div id="file_'.$objFile->id.'" class="filerow">'
								.'<a href="javascript:tf_del_file('.$objFile->id.')">X</a>'
								.$objFile->filename.'</div>';
						}
					}
				}
				?>
				</div>
			</li>
		</ol>
	</fieldset>
	<?php } ?>
	<p>
		<?php
		if ($this->task->isLoaded() && 
			($GLOBALS['objUser']->hasAccess(14, 'taskfreak') || $this->task->checkRights($GLOBALS['objUser']->id,9,false))
		) {
		?>
		<a class="button delete frgt" href="<?php echo $this->getUrl('delete',$this->task->id); ?>" 
			onclick="return confirm('Voulez-vous vraiment supprimer cette t&acirc;che ?')">Supprimer la t&acirc;che</a>
		<?php
		}
		?>
		<button type="submit" name="save" value="1" class="save">Enregistrer t&acirc;che</button>
		<a href="<?php echo $this->getUrl('view',$this->task->id); ?>" class="close">annuler</a>
	</p>
	<input type="hidden" id="upload_mode" name="uploadmode" value="" />
	<iframe name="upload_iframe" style="width: 400px; height: 1px; border: 0px;"></iframe>
	</form>
</div>
<hr class="clear" />
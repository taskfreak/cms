<div id="tf_head">
	<?php
	/*
	if (trim($this->intro->body)) {
		$this->intro->printContent();
	}
	*/
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
	
	/* --- Task Info ---------------------------------------------------------- */
	
	?>
	<h1><?php echo $this->task->p('title');?></h1>
	<div id="tf_info">
		<?php
		if ($this->rights['userCanEdit'] || $this->rights['userCanDelete']) {
			echo '<div id="tf_tools">';
			if ($this->rights['userCanEdit']) {
				echo '<p><a href="'.$this->getUrl('edit',$this->task->id).'"><img src="/assets/images/b_edt.png" alt="edit" />&nbsp;'.$GLOBALS['langTaskForm']['edit'].'</a></p>';
			}
			/*
			if ($this->rights['userCanDelete']) {
				echo '<p><a href="'.$this->getUrl('delete',$this->task->id).'"><img src="/assets/images/b_rem.png" alt="delete" />&nbsp;'.$GLOBALS['langTaskForm']['delete'].'</a></p>';
			}
			*/
			echo '</div>';
		}
		?>
		<h4>
			<?php
				echo $this->taskOrigin->member->getAvatar();
				echo $this->task->project->p('name'); 
			?>
			<small><?php
				$strVisi = '<img src="'.FRK_IMAGES.'priv0.png" width="12" height="16"  border="0" alt="" /> '
					.$GLOBALS['langTaskForm']['public'];
				switch ($this->task->showPrivate) {
					case 1:
						$strVisi = '<img src="'.FRK_IMAGES.'priv1.png" width="12" height="16" border="0" alt="" /> '
							.$GLOBALS['langTaskForm']['internal'];
						break;
					case 2:
						$strVisi = '<img src="'.FRK_IMAGES.'priv2.png" width="12" height="16" border="0" alt="" /> '
							.$GLOBALS['langTaskForm']['private'];
						break;
				}
				echo $strVisi;
			?></small>
		</h4>
		<div id="tf_history" class="panel">
		<?php
		
		// --- Author and Post date --------------------
	
		echo '<p>';
		echo 'Par <strong>'.$this->taskOrigin->member->getName().'</strong></p>'; 
		echo '<p>Le <strong>'.$this->taskOrigin->getDtm('statusDate','LNX',$GLOBALS['objUser']->timeZone).'</strong>';
		echo ' <small>(<a href="javascript:tooglepanel(\'history\')">historique</a>)</small>';
		echo '</p>';

		// --- Status history --------------------------
		
		echo '<table id="history_panel" class="fields"><thead><tr>'
			.'<th>'.$GLOBALS['langTaskDetails']['history_date'].'</th>'
			.'<th>'.$GLOBALS['langTaskDetails']['history_user'].'</th>'
			.'<th>'.$GLOBALS['langTaskDetails']['history_what'].'</th></tr></thead><tbody>';
		while ($objStatus = $this->taskStatus->rNext()) {
			echo '<tr><td>'.$objStatus->getDtm('statusDate','SHT',$GLOBALS['objUser']->timeZone).'</td>'
				.'<td>'.$objStatus->member->getName().'</td>'
				.'<td>'.$GLOBALS['langItemStatus'][$objStatus->statusKey].'</td></tr>';
		}
		echo '</tbody>';
		if ($this->rights['userCanStatus']) {
			echo '<tfoot>';
			echo '<tr><td>changer &eacute;tat :</td>';
			echo '<td colspan="2">';
			echo '<form id="status_form" action="/ajax.php" method="post">';
			echo '<input type="hidden" name="module" value="taskfreak" />';
			echo '<input type="hidden" name="action" value="status" />';
			echo '<input type="hidden" name="item" value="'.$this->task->id.'" />';
			$this->task->qSelectStatus('status','onchange="ajaxify_form($(\'status_form\'),\'curstatus\',\'status_form\')" style="width:150px"');
			echo '</form>';
			echo '</td></tr></tfoot>';
		}
		
		echo '</table>';
		?>
		</div>
		<?php
		
		// --- More information ------------------------
		
		?>
		<ol>
			<li>
				<label><?php echo $GLOBALS['langTaskForm']['priority']; ?> :</label>
				<span class="prio pr<?php  echo $this->task->priority; ?>"><?php echo $this->task->priority; ?></span>
			</li>
			<li>
				<label>Ech&eacute;ance :</label>		
				<?php echo $this->task->getDeadline(); ?>
			</li>
			<li>
				<label>Assign&eacute; &agrave; :</label>
				<?php
					$str = $this->task->member->getName();
					if (trim($str)) {
						echo $str; 
					} else {
						echo '-'; 
					}
				?>
			</li>
			<li>
				<label><?php echo $GLOBALS['langTaskForm']['status']; ?> :</label>
				<?php
				echo '<span id="curstatus">'.$GLOBALS['langItemStatus'][$this->task->itemStatus->statusKey].'</span>';
				if ($this->rights['userCanStatus']) {
					echo ' <small>(<a href="javascript:tooglepanel(\'history\')">modifier</a>)</small>';
				}
				?>
			</li>
		</ol>
	</div>
	<?php
	
	/* --- Message / error ------------------------------------------------------- */
	
	if ($this->message) {
		echo '<div class="highlight mellow">'.$this->message.'</div>';
	}
	
	/* --- Description ----------------------------------------------------------- */
	
	?>
	<div id="tf_desc">
	<?php
	
	// --- attachments ? ---------------------------
	
	if ($this->files->rMore()) {
		echo '<ul id="tf_files">';
		while ($objFile = $this->files->rNext()) {
			echo '<li><a href="'.$objFile->getFileUrl().'" target="_blank">'.utf8_decode($objFile->get('fileTitle')).'</a></li>';
		}
		echo '</ul>';
	}
	
	// --- task body -------------------------------
	
	echo '<p>';	
	$this->task->p('description');
	echo '</p>';
	?>
	</div>
	<?php
	
	/* --- Comments ----------------------------------------------------------- */
	
	if (is_object($this->comments)) {
		// user is allowed to see comments
	?>
	<div id="tf_comm" class="comment_list">
		<h3><?php echo $GLOBALS['langComment']['comments']; ?></h3>
		<?php
		
		// --- list comments ---------------------------
		
		if ($this->comments->rMore()) {
			
			while ($objComment = $this->comments->rNext()) {
		?>
		<div id="comment_<?php echo $objComment->id; ?>" class="comment_item">
		<?php
			// check rights to edit and/or delete comment
			$userCanEdit = $objComment->checkRights($GLOBALS['objUser']->id,3,$this->task);
			$userCanDelete = $objComment->checkRights($GLOBALS['objUser']->id,4,$this->task);
			
			if ($userCanEdit || $userCanDelete) 
			{
			?>
			<div class="comment_action">
				<?php
				if ($userCanEdit) { 
				?>
				<a href="/ajax.php?module=taskfreak&amp;action=comedit&amp;id=<?php echo $objComment->id; ?>" rel="ajax comment_body_<?php 
					echo $objComment->id; ?>"><?php echo $GLOBALS['langComment']['edit']; ?></a>
				<?php 
				} 
				if ($userCanEdit && $userCanDelete) { echo ' | '; }
				if ($userCanDelete) { 
				?>
				<a href="javascript:{}" onclick="if (confirm('<?php echo $GLOBALS['langComment']['delete_confirm']; ?>')) ajaxify_request('/ajax.php?module=taskfreak&amp;action=comdelete&amp;id=<?php echo $objComment->id; ?>');"><?php echo $GLOBALS['langComment']['delete']; ?></a>
				<?php } ?>
			</div>
			<?php
			}			
		?>
		<div class="comment_head"><?php
			echo $objComment->member->getAvatar(); 
			echo '<p>'.$objComment->member->getShortName().'</p>';
		?></div>
		<p class="comment_date"><?php echo $objComment->getDtm('postDate','LNX'); ?></p>
		<div id="comment_body_<?php echo $objComment->id; ?>" class="comment_body"><?php
			$objComment->p('body'); 
		?></div>
		</div>
		<?php
			}
		} else {
			// --- no comment found ---
			echo '<p>'.$GLOBALS['langComment']['none'].'</p>';
		}
		
		// --- post new comment ------------------------
		
		if (is_object($this->postcomment)) {
			if (@constant('CMS_REWRITE_URL')) {
				echo '<form id="comment_form" action="'.$GLOBALS['objPage']->getUrl().'" method="post" enctype="multipart/form-data">';
			} else {
				echo '<form id="comment_form" action="'.CMS_WWW_URI.'" method="post" enctype="multipart/form-data">';
				echo '<input type="hidden" name="page" value="'.$GLOBALS['objPage']->id.'" />';
			}
			echo '<input type="hidden" name="action" value="view" />';
			echo '<input type="hidden" name="item" value="'.$this->task->id.'" />';
			echo '<fieldset>';
			echo '<legend>'.$GLOBALS['langComment']['post'].'</legend>';
			echo '<ol id="comment_panel" class="fields top">';
			echo '<li>';
			$this->postcomment->qTextArea('comment_body','','wxl hm');
			echo '</li>';
			echo '<li><label>Ajouter un fichier</label>';
			echo '<input type="file" id="i_newfile" name="newfile" value="" onchange="tf_upload()" />';
			echo '<button type="button" id="fileadd" onclick="tf_upload()" title="Formats acc&eacute;pt&eacute;s<br /><small>.pdf, .doc, .zip, .jpg, .png, .gif<br />taille max:'
				.CMS_UPLOAD_MAX_SIZE.'</small>">Ajouter</button>';
    	  	echo '<div id="tf_attachs" class="nomarge"></div>';
			echo '<li class="linefree"><button type="submit" name="postComment" value="1" class="submit">'.$GLOBALS['langComment']['post_submit'].'</button></li>';
			echo '</ol>';
			echo '</fieldset>';
			echo '<input type="hidden" id="upload_mode" name="uploadmode" value="" />';
			echo '<iframe name="upload_iframe" style="width: 400px; height: 1px; border: 0px;"></iframe>';
			echo '</form>';
		}
	} // end comments		
?>
</div>
<hr class="clear" />
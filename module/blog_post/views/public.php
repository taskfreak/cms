<?php

if ($this->intro->getOption('member_only') && !$GLOBALS['objUser']->isLoggedIn()) {

	// no access for the non member
	$this->more->printContent();

} else if ($this->success) {

	// article subtmited, say thanks
	$this->more->printContent();
	
} else {

	// can submit article, show the form

	$this->intro->printContent();
	
	?>
<form action="<?php echo $GLOBALS['objPage']->getUrl(); ?>" method="post" enctype="multipart/form-data">
	<fieldset>
		<legend>En-t&ecirc;te de l'article</legend>
		<ol class="fields top">
			<li>
				<label><?php echo $GLOBALS['langBlogAdminForm']['title']; ?> :</label>
				<?php $this->content->_join->qText('title','','wxl',$strUpdAuto); ?>
			</li>
			<?php
			// event or not
			switch ($this->intro->getOption('allowed_types')) {
			case 1: // articles only
				break;
			case 2: // events only
			?>
			<li>
				<label><?php echo $GLOBALS['langBlogAdminForm']['event_date_begin']; ?> :</label>
				<?php $this->content->_join->qDateSelect('cms_begin','eventStart',''); ?>
			</li>
			<li>
				<label><?php echo $GLOBALS['langBlogAdminForm']['event_date_end']; ?> :</label>
				<?php $this->content->_join->qDateSelect('cms_end','eventStop',''); ?>
			</li>
			<?php
				break;
			default: // articles or events
			?>
			<li class="inline">
				<?php $this->content->_options->qCheckBox('option_is_event','','','onclick="cms_toggle(\'publish_event\')"'); ?>
				<label for="c_option_is_event"><?php echo $GLOBALS['langBlogAdminForm']['event_is_event']; ?></label>
				<ul id="publish_event" style="margin-left:20px;display:<?php echo ($this->content->_options->get('option_is_event'))?'block':'none'; ?>">
					<li>
						<label><?php echo $GLOBALS['langBlogAdminForm']['event_date_begin']; ?> :</label>
						<?php $this->content->_join->qDateSelect('cms_begin','eventStart',''); ?>
					</li>
					<li>
						<label><?php echo $GLOBALS['langBlogAdminForm']['event_date_end']; ?> :</label>
						<?php $this->content->_join->qDateSelect('cms_end','eventStop',''); ?>
					</li>
				</ul>			
			</li>
			<?php
				break;
			}
			?>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Corps de l'article</legend>
		<?php
		
		$this->content->qEditArea();
		
		?>
	</fieldset>
	<p><button type="submit" name="save" value="1" class="save">Soumettre l'article</button></p>
	<p>Toutes les informations envoy&eacute;es (textes et photos) doivent &ecirc;tre libres de droit afin d'&ecirc;tre publi&eacute;es sur ce site.<br />
	La publication se fait apr&egrave;s v&eacute;rification par un mod&eacute;rateur, et peut prendre jusqu'&agrave; 24h.</p>
</form>
<?php

}
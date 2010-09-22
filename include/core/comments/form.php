<?php
if (@constant('CMS_REWRITE_URL')) {
	echo '<form id="comment_form" action="'.$GLOBALS['objPage']->getUrl().'" method="post">';
} else {
	echo '<form id="comment_form" action="'.CMS_WWW_URI.'" method="post">';
	echo '<input type="hidden" name="page" value="'.$this->content->pageId.'" />';
}
	echo '<input type="hidden" name="item" value="'.$this->content->id.'" />';

	if (!$GLOBALS['objUser']->isLoggedIn()) {
		echo '<fieldset class="panel">';
		echo '<legend><a href="javascript:tooglepanel(\'comment\')">'.$GLOBALS['langComment']['post'].'</a></legend>';
	} else {
		echo '<fieldset>';
		echo '<legend>'.$GLOBALS['langComment']['post'].'</legend>';
	}
	echo '<ol id="comment_panel" class="fields top">';
	if (!$GLOBALS['objUser']->isLoggedIn()) {
		echo '<li class="compulsory"><label>'.$GLOBALS['langForm']['name'].' :</label>';
		Tzn::qText('option_author_name',$this->postcomment->getOption('author_name'),'wl');
		echo '</li>';
		echo '<li class="compulsory"><label>'.$GLOBALS['langForm']['email'].' :</label>';
		Tzn::qText('option_author_email',$this->postcomment->getOption('author_email'),'wl');
		echo '</li>';
		echo '<li class="compulsory"><label>';
		echo $GLOBALS['langComment']['post_body'].' :</label>';
		$this->postcomment->qTextArea('body','','wxl hm');
		echo '</li>';
		
		// captcha
		echo $GLOBALS['objPage']->getCaptcha(!$GLOBALS['objUser']->isLoggedIn(),'li',$_POST['saveComment']?true:false);
		echo '<li class="linefree">Tous les champs sont obligatoires. Votre adresse e-mail ne sera ni publi&eacute;e, ni revendue &agrave; des tiers.</li>';
		
	} else {
		echo '<li>';
		$this->postcomment->qTextArea('body','','wxl hm');
		echo '</li>';
	}
	echo '<li class="linefree"><button type="submit" name="saveComment" value="1" class="submit">'.$GLOBALS['langComment']['post_submit'].'</button></li>';
	echo '</ol>';
?>
</fieldset>
</form>
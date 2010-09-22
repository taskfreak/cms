<?php
$objData = $this->objMember->getPlugin('member_news_letter');
?>
<div class="frgt" style="width:50%"><?php
	if ($objData->subscribeEnable) {
		echo '<p>Inscrit depuis le : '
			.'<strong>'.$objData->getDte('subscribeDate','LNG').'</strong></p>';
	}
	if ($objData->sentAlready()) {
		echo '<p>Derni&egrave;re lettre envoy&eacute;e le : '
			.'<strong>'.$objData->getDte('newsletterDate','LNG').'</strong></p>';
	}
	?>
</div>
<ol class="fields">
	<li class="inline">
		<?php $objData->qCheckbox('subscribeEnable','','','onclick="$(\'subs_more\').toggleClass(\'faded\')"'); ?>
		<label for="c_subscribeEnable">Inscrit &agrave; la newsletter</label>
		<ul id="subs_more"<?php echo ($objData->subscribeEnable)?'':' class="faded"'; ?>>
			<li>
				<input type="radio" id="c_html1" name="subscribeHtml" value="1" <?php
					if ($objData->subscribeHtml) { echo 'checked="checked" '; }
				?>/>
				<label for="c_html1">Format HTML</label>
			</li>
			<li>
				<input type="radio" id="c_html0" name="subscribeHtml" value="0" <?php
					if (!$objData->subscribeHtml) { echo 'checked="checked" '; }
				?>/>
				<label for="c_html0">Format texte</label>
			</li>
		</ul>
	</li>
</ol>
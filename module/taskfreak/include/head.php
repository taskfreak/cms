	<hr class="clear" />
	<form action="<?php echo $GLOBALS['objPage']->getUrl(); ?>" method="get">
		<input id="i_search" type="text" name="search" value="<?php echo $this->req['search']; ?>" placeholder="mots-cl&eacute;s" />
		<button type="submit" class="tf_submit" title="Lancer la recherche">Chercher</button>
		<button type="submit" onclick="$('i_search').set('value','')" class="tf_reset" title="Annuler la recherche">Annuler</button>
	</form>
	<ul id="tf_filter">
		<li<?php if ($this->req['show']=='recent') echo ' class="current"'; ?>><a href="<?php echo TznUtils::concatUrl($this->req['link'],'show=recent'); ?>" accesskey="a">Mises &agrave; jour</a></li>
        <li<?php if ($this->req['show']=='sta0,1') echo ' class="current"'; ?>><a href="<?php echo TznUtils::concatUrl($this->req['link'],'show=sta0,1'); ?>" accesskey="b">Nouvelles &amp; En cours</a></li>
        <li<?php if ($this->req['show']=='sta2') echo ' class="current"'; ?>><a href="<?php echo TznUtils::concatUrl($this->req['link'],'show=sta2'); ?>" accesskey="b">Termin&eacute;es</a></li>
        <li<?php if ($this->req['show']=='sta3') echo ' class="current"'; ?>><a href="<?php echo TznUtils::concatUrl($this->req['link'],'show=sta3'); ?>" accesskey="b">Archiv&eacute;es</a></li>
        <?php /*
        <li<?php if ($this->req['show']=='future') echo ' class="current"'; ?>><a href="<?php echo Tzn::concatUrl($pTmpLink,'show=future'); ?>" accesskey="c"><?php echo $GLOBALS['langMenu']['future_tasks']; ?></a></li>
        <li<?php if ($this->req['show']=='past') echo ' class="current"'; ?>><a href="<?php echo TznUtils::concatUrl($this->req['link'],'show=past'); ?>" accesskey="d"><?php echo $GLOBALS['langTaskMenu']['past_tasks']; ?></a></li>
        */ ?>
        <li<?php if ($this->req['show']=='all') echo ' class="current"'; ?>><a href="<?php echo TznUtils::concatUrl($this->req['link'],'show=all'); ?>" accesskey="e"><?php echo $GLOBALS['langTaskMenu']['all_tasks']; ?></a></li>
    </ul>
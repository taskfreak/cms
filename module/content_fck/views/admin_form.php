<h3 class="acctog">Contenu</h3>
<div class="accinf">
<?php
	$this->content->qEditArea();
?>
</div>
<h3 class="acctog">Options</h3>
<div class="accinf">
	<ol class="fields">
		<li class="inline">
			<input type="checkbox" id="opt1" name="option_sitemap_inc" value="1"<?php
			if ($this->content->getOption('sitemap_inc')) echo ' checked="checked"';
			?> onclick="$('opt1_more').toggleClass('faded')" />
			<label for="opt1">Afficher le plan du site</label>
			<ul id="opt1_more"<?php echo ($this->content->getOption('sitemap_inc'))?'':' class="faded"'; ?>>
				<li>
					<label for="opt1_1">Selectionnez la page de d&eacute;part</label>
					<?php
						$this->content->_sitemap->qSelect('option_sitemap_idx', 'menu', $this->content->getOption('sitemap_idx'));
					?>
				</li>
			</ul>
		</li>
		<li class="inline">
			<input type="checkbox" id="opt2" name="option_blog_inc" value="1"<?php
			if ($this->content->getOption('blog_inc')) echo ' checked="checked"';
			?> onclick="$('opt2_more').toggleClass('faded')" />
			<label for="opt2">Inclure les articles du blog</label>
			<ul id="opt2_more"<?php echo ($this->content->getOption('blog_inc'))?'':' class="faded"'; ?>>
				<li>
					<label for="opt2_1">Saisir la cat&eacute;gorie &agrave; afficher</label>
					<input type="text" name="option_blog_tag" value="<?php echo $this->content->getOption('blog_tag'); ?>" />
				</li>
			</ul>
		</li>
	</ol>
</div>
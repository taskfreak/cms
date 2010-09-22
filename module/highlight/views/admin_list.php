	<div class="info">
		<a href="<?php echo $this->baseLink.'&amp;action=edit';	?>" class="button create frgt"><?php 
			echo $GLOBALS['langModule']['picture_gallery']['admin_add']; 
		?></a>
		<hr class="sep" />
	</div>
<?php
if ($this->data->rMore()) {

/* --- LIST OF ITEMS ------------------------------------------- */

?>
	<ul id="piclist" class="clickable">
<?php
	while ($objItem = $this->data->rNext()) {
?>
	<li>
		<input type="hidden" name="pic[]" value="<?php echo $objItem->id; ?>" />
		<strong><?php echo $objItem->getTitle(); ?></strong>
		<span class="img"><?php $objItem->_join->pImg('imgfile','',120,90,'','home'); ?></span>
		<span>
			<a href="<?php echo $this->baseLink.'&amp;action=edit&amp;item='.$objItem->id; ?>" rel="clickme"><?php echo $GLOBALS['langAdmin']['edit']; ?></a>
			|
			<a href="<?php echo $this->baseLink.'&amp;action=delete&amp;item='.$objItem->id; ?>" onclick="return confirm('<?php echo $GLOBALS['langAdmin']['del_confirm']; ?>')"><?php echo $GLOBALS['langAdmin']['delete']; ?></a> 
		</span>
	</li>
<?php
	}
?>
	</ul>
	<hr class="clear" />
<?php
} else {

/* --- NO ITEM FOUND ------------------------------------------- */

?>
<p class="empty ctr">- <?php echo $GLOBALS['langTznCommon']['list_empty']; ?> -</p>
<?php
}
?>
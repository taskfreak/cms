<h3 class="acctog">Liste des projets</h3>
<div class="accinf">
	<?php
    if ($this->data->rMore()) {
   	?>
	<div class="table clickable hl">
		<div class="header">
			<div class="col c40"><?php echo $GLOBALS['langProject']['project']; ?></div>
			<div class="col c10"><?php echo $GLOBALS['langProject']['position']; ?></div>
			<div class="col c10 ctr"><?php echo $GLOBALS['langProject']['members']; ?></div>
			<div class="col c10"><?php echo $GLOBALS['langProject']['status']; ?></div>
			<div class="col c10 ctr"><?php echo $GLOBALS['langTaskForm']['tasks']; ?></div>
			<div class="col c20 action"><?php
                if ($GLOBALS['objUser']->hasAccess(7, $this->folder)) {
                    ?><a href="<?php echo $this->baseLink.'&amp;action=edit'; ?>">nouveau projet</a><?php
                } else {
                    echo '&nbsp;';
                }
            ?></div>
		</div>
		<?php
        while ($objItem = $this->data->rNext()) {
            if ($objItem->projectStatus->statusKey < 40) {
                $rowStyle = '';
            } else {
                $rowStyle = ' disabled';
            }
		?>
		<div class="row<?php echo $rowStyle; ?>">
			<div class="col c40">
				<a href="<?php echo TznUtils::concatUrl($GLOBALS['objPage']->getUrl(),'project='.$objItem->id); ?>"><?php $objItem->p('name'); ?></a>
			</div>
			<div class="col c10"><?php $objItem->pPosition(); ?></div>
			<div class="col c10 ctr"><?php $objItem->p('memberCount'); ?></div>
			<div class="col c10"><?php $objItem->pStatus(); ?></div>
			<div class="col c10 ctr"><?php $objItem->p('itemCount',0); ?></div>
			<div class="col c20 action">
                <?php
                if ($objItem->memberProject->checkRights(14) || ($GLOBALS['objUser']->hasAccess(9, $this->folder))) {
                ?>
				<a href="<?php echo $this->baseLink.'&amp;action=delete&amp;item='.$objItem->id; ?>" onclick="return confirm('<?php echo $GLOBALS['langMessage']['project_delete_confirm']; ?>');" title="<?php echo $GLOBALS['langMessage']['project_delete']; ?>">supprimer</a>
                <?php
                } else {
                ?>
				<span>supprimer</span>
                <?php
                }
                ?>
                <a href="<?php echo $this->baseLink.'&amp;action=edit&amp;item='.$objItem->id; ?>" rel="clickme">modifier</a>
            </div>
		</div>
		<?php
		}
	?>
	</div>
	<?php
	} else {
	?>
            <p class="empty">- <?php echo $GLOBALS['langMessage']['no_project_found']; ?> -</p>
    <?php
    }
	?>
</div>
<h3 class="acctog">Introduction</h3>
<div class="accinf">
	<?php
		$this->content->qEditArea();
	?>
</div>
<h3 class="acctog">Options</h3>
<div class="accinf">
	<ol class="fields">
		<li>
			<label>T&acirc;ches / Page :</label>
			<input type="text" name="option_pagination" class="wxs" value="<?php
				if ($this->content->getOption('pagination')) {
					echo $this->content->getOption('pagination');
				} else {
					echo '10'; // -TODO- default from settings
				}
			?>" />
			<small>Nombre d'articles &agrave; afficher par page</small>
		</li>
	</ol>
</div>
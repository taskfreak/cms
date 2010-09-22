<div id="tf_head">
	<?php
	if (trim($this->intro->body)) {
		$this->intro->printContent();
	}
	include $this->filePath('include/head.php');
	?>
</div>
<div id="tf_nav">
	<?php
	if ($GLOBALS['objUser']->hasAccess(11,'taskfreak')) {
		echo '<ul><li><a href="'.$this->getUrl('edit').'" accesskey="n">Cr&eacute;er une t&acirc;che</a></li></ul>';
	}
	?>
	<ul class="lst">
<?php
	echo '<li'
		.(($this->req['project'])?'':' class="current"')
		.'><a href="'.TznUtils::concatUrl($this->req['link'],'project=*').'">Tous projets</a></li>';
	while ($objProj = $this->projects->rNext()) {
		echo '<li'.(($this->req['project'] == $objProj->id)?' class="current"':'').'>';
		echo '<a href="'.TznUtils::concatUrl($this->req['link'],'project='.$objProj->id).'">'.$objProj->getShortName().'</a></li>';
	}
?>
	</ul>
</div>
<div id="tf_main">
	<table id="taskSheet" class="sheet">
        <thead>
            <tr>
            	<?php $this->pSortTh('&nbsp', '2%', 'priority'); ?>
            	<?php $this->pSortTh('Ech&eacute;ance', '8%', 'deadlineDate'); ?>
                <?php
                    $titleWidth = 0;
                    $titleLabel = '&nbsp;';
                    if (@constant('FRK_CONTEXT_ENABLE')) {
                        if (@constant('FRK_CONTEXT_LONG')) {
                            $titleWidth = 10;
                            $titleLabel = $GLOBALS['langForm']['context'];
                        } else {
                            $titleWidth = 2;
                        }
                        $this->pSortTh($titleLabel, $titleWidth.'%', 'context');
                    }

                ?>
                <th width="<?php echo 57-$titleWidth-(FRK_STATUS_LEVELS * 2); ?>%">Description de la t&acirc;che</th>
                <?php $this->pSortTh($GLOBALS['langTaskForm']['user'], '11%', 'mm.username'); ?>
                <?php $this->pSortTh('Mise &agrave; jour', '11%', 'lastChangeDate'); ?>
				<th width="6%"><?php echo $GLOBALS['langTaskForm']['list_comments']; ?></th>
                <th width="<?php echo FRK_STATUS_LEVELS * 2; ?>%"><?php echo (FRK_STATUS_LEVELS == 1)?'X':$GLOBALS['langTaskForm']['status']; ?></th>
            </tr>
        </thead>
        <tbody>
<?php

// ----------- TASK LIST (CONTENT) -------------------------------------------

if ($this->data->rMore()) {
    while ($objItem = $this->data->rNext()) {
        $priority = $objItem->priority;
?>
            <tr id="erow-<?php echo $objItem->id; ?>" class="easyclick">
                <td class="prio"><span class="pr<?php echo $priority; ?>" 
                	title="<?php echo $_GLOBALS['arrPriorities'][$objItem->priority]; ?>"><?php echo $priority; ?></span></td>
                <td class="date"><?php $objItem->pDeadline(); ?></td>
            <?php
                if (@constant('FRK_CONTEXT_ENABLE')) {
            ?>
                <td class="<?php echo (@constant('FRK_CONTEXT_LONG'))?'ctlg':'ctsh'; ?>"><?php 
                	echo $objItem->getContext(@constant('FRK_CONTEXT_LONG')); 
                ?></td>
                <?php
                }
            ?>
                <td class="ecol"><?php  
                    echo $objItem->getSummary($this->getUrl('view',$objItem->id));
                    echo '<br /><small>'.$objItem->project->getShortName().'</small>';
                ?></td>
                <td class="eusr"><?php echo $objItem->member->getShortName('-'); ?></td>
				<td class="ecol eupd"><?php 
					echo $objItem->getListStat(); 
				?></td>
				<td><div style="float:right" id="ecomm<?php echo $objItem->id; ?>"><?php echo $objItem->p('itemCommentCount','0'); ?></div><a href="<?php echo $this->getUrl('view',$objItem->id); ?>#tf_comm"><img src="<?php echo FRK_IMAGES.'b_disc.png'; ?>" width="14" height="16" alt="commentaires" border="0" /></a></td>
				<td class="sts"><?php
	                $s = $objItem->itemStatus->statusKey;
	                for ($i = 0; $i < FRK_STATUS_LEVELS; $i++) {
	                    $j = ($i < $s)?(FRK_STATUS_LEVELS - $i):0;
	            		echo '<a id="est'.($i+1).'-'.$objItem->id.'" class="sts'.$j.'"';
	                	$tip = 'Etat actuel : '.$GLOBALS['langItemStatus'][$s];
	                    if ($GLOBALS['objUser']->hasAccess(14, 'taskfreak') || $objItem->checkRights($GLOBALS['objUser']->id,8,true))  {
	                        echo ' href="javascript:tf_status('.$objItem->id.','.($i+1).');"';
	                        // echo ' onclick="return confirm(\'Modifier la t&acirc;che ?\')"';
	                        echo 'style="cursor:pointer"';
	                        $tip .= '<br /><small>cliquer pour marquer comme<br />&laquo; t&acirc;che '.$GLOBALS['langItemStatus'][$i+1].' &raquo;</small>';
	                    } else {
	                    	echo ' href="javascript:{}"';
	                    }
	                    echo ' title="'.$tip.'">&nbsp;</a>';
					}
				?><br /><small id="estx-<?php echo $objItem->id; ?>"><?php echo $GLOBALS['langItemStatus'][$s]; ?></small></td>
				<?php                
                /*
                <td class="act">
                <?php
                  // EDIT
                  if ($GLOBALS['objUser']->hasAccess(14, 'taskfreak') || $objItem->checkRights($GLOBALS['objUser']->id,7))  {
                ?><a href="javascript:freak_edit(<?php echo $objItem->id; ?>)" title="<?php echo $GLOBALS['langMessage']['task_edit']; ?>"><img src="<?php echo FRK_IMAGES.'b_edit.png'; ?>" width="20" height="16" alt="edit" border="0" /></a><?php
                  } else {
                ?><img src="<?php echo FRK_IMAGES.'b_edin.png'; ?>" width="20" height="16" alt="del" border="0" /><?php
                  }
                  // DELETE
                  if ($GLOBALS['objUser']->hasAccess(14, 'taskfreak') || $objItem->checkRights($GLOBALS['objUser']->id,9))  {
                ?><a href="javascript:freak_del(<?php echo $objItem->id; ?>)" onClick="return confirm('<?php echo $GLOBALS['langMessage']['task_delete_confirm']; ?>')" title="<?php echo $GLOBALS['langMessage']['task_delete']; ?>"><img src="<?php echo FRK_IMAGES.'b_dele.png'; ?>" width="20" height="16" alt="del" border="0" /></a><?php
                  } else {
                ?><img src="assets/skins/<?php echo FRK_SKIN_FOLDER; ?>/images/b_deln.png" width="20" height="16" alt="del" border="0" /><?php
                  }
                ?>
                </td>
                */
                ?>
            </tr>
<?php
}
} else {

// --------------- NO TASK FOUND ---------------------------------------------

?>
            <tr class="nothanks">
                <td colspan="<?php echo (@constant('FRK_CONTEXT_ENABLE'))?'13':'12'; ?>">
                    <p>&nbsp;</p>
                    <p align="center">- <?php echo $GLOBALS['langMessage']['no_task_found']; ?> -</p>
                    <?php
                        if ($GLOBALS['objUser']->hasAccess(11, 'taskfreak')) {
                    ?>
                    <p align="center"><a href="javascript:freak_new()"><?php echo $langMessage['create_task']; ?></a></p>
                    <?php
                        }
                    ?>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                </td>
            </tr>
<?php
}
?>
        </tbody>
    </table>
</div>
<hr class="clear" />
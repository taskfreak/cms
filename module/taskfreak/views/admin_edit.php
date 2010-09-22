<h3 class="section"><?php echo $GLOBALS['langProject']['project_info']; ?></h3>
<div class="accinf">
    <?php
	if ($this->rights['userCanEdit']) {
		echo '<div class="info">';
		if ($this->error) {
			echo '<p class="tznError">'.$this->error.'</p>';
		} else {
			echo '<p>'.$GLOBALS['langForm']['compulsory_legend'].'</p>';
		}
		echo '</div>';
	}
    ?>
    <ol class="fields side">
    	<li class="compulsory">
    		<label><?php echo $GLOBALS['langProject']['name']; ?></label>
    		<?php
                if ($this->rights['userCanEdit']) {
                    $this->content->qText('name','','width:400px'); 
                } else {
                    echo '<big>'.$this->content->get('name').'</big>';
                }
            ?>
    	</li>
    	<li>
    		<label><?php echo $GLOBALS['langProject']['description']; ?></label>
    		<?php
                if ($this->rights['userCanEdit']) {
                    $this->content->qTextArea('description','','width:400px;height:60px');
                } else {
                    $this->content->p('description','-');
                }
            ?>
    	</li>
    	<li>
    		<label><?php echo $GLOBALS['langProject']['status']; ?></label>
    		<?php
                if ($this->rights['userCanStatus']) {
					$this->statusList->qSelect('status',$this->content->projectStatus->statusKey,'','width:150px'); 
                    if ($this->content->id) {
                        echo ' <a href="javascript:cms_toggle(\'status_history\')" class="small">&gt; '.$GLOBALS['langProject']['project_history'].'</a>';
                    }
                } else {
            ?>
                <a href="javascript:cms_toggle('status_history')"><?php echo $GLOBALS['langProjectStatus'][$this->content->projectStatus->statusKey]; ?></a>
            <?php
                }
			?>
    	</li>
    	<?php
		if ($this->content->id) {
        ?>
        <div id="status_history" style="display:none">
            <table class="sheet" style="margin-left:110px;width:60%">
                <tr>
                    <th><?php echo $GLOBALS['langProject']['history_date']; ?></th>
                    <th><?php echo $GLOBALS['langProject']['history_what']; ?></th>
                    <th><?php echo $GLOBALS['langProject']['history_user']; ?></th>
                </tr>
        <?php
                while ($objTmp=$this->statusHistory->rNext()) {
        ?>
                <tr>
                    <td><?php echo $objTmp->getDte('statusDate','SHT'); ?></td>
                    <td><?php echo $objTmp->getStatus(); ?></td>
                    <td><?php echo $objTmp->member->getShortName(); ?></td>
                </tr>
        <?php
                }
        ?>
            </table>
        </div>
        <?php
		}
        ?>
    </ol>
</div>
<?php
if ($this->content->isLoaded()) {
?>
<h3 class="section"><?php echo $GLOBALS['langProject']['members_legend']; ?></h3>
<div class="accinf">
	<?php
    $this->positionList->pJSarray();
	?>
	<div class="table hl">
	<?php
	$firstcol = ($this->rights['userCanManage'])?'c40':'c60';
	while ($objMemberTeam = $this->memberList->rNext()) {
	?>
		<div class="row">
			<div class="col <?php echo $firstcol; ?>">
				<?php 
                    if ($GLOBALS['objUser']->hasAccess(13)) { 
                ?>
                    <a href="/admin/user.php?id=<?php echo $objMemberTeam->member->id; ?>"<?php
					    if (!$objMemberTeam->position) {
						    echo ' class="disabled"';
						}
    				?>><?php 
                        echo $objMemberTeam->member->getName();
                    ?></a><?php
                    } else {
                        echo $objMemberTeam->member->getName();
                    }
                ?>
			</div>
			<div class="col c30">
				<?php echo $objMemberTeam->member->getLocation($arrCountry); ?>
			</div>
			<div class="col c15" id="pos-<?php echo $objMemberTeam->member->id; ?>">
				<?php $objMemberTeam->pPosition(); ?>
			</div>
			<?php 
				
				// -- user rights management action links --
				
				if ($this->rights['userCanManage']) { 
					// user has rights to edit/delete users
			?>
			<div class="col c15 action" id="but-<?php echo $objMemberTeam->member->id; ?>">
				<?php	
					if (($GLOBALS['objUser']->id != $objMemberTeam->member->id)
						&& ($objMemberTeam->position < $this->userPosition))
					{ 
				?>
				<a href="<?php echo $this->baseLink.'&remove='.$objMemberTeam->member->id; ?>" onclick="return confirm('retirer cet utilisateur du projet?');">retirer</a>
				<a href="javascript:tf_team_edit(<?php echo $objMemberTeam->member->id.','.$objMemberTeam->position.','.$this->userPosition ?>)">droits</a>
				<?php
					} else {
						echo '<span>retirer</span> <span>droits</span>';
					}
				?>
			</div>
			<?php
				
				} // end rights management action links
				
			?>
		</div>
	<?php
	} // end loop through users
	?>
	</div>
	<?php
	if ($this->rights['userCanManage'] && $this->otherUserList->rMore()) { 
        // user has rights to add/edit/delete users
    ?>
    <div style="padding: 8px 50% 4px 4px">
	    <p>&gt; <a href="javascript:cms_toggle('invitation')" class="more"><?php echo $GLOBALS['langProject']['user_add_legend']; ?></a></p>
	    <ol id="invitation" class="fields side" style="display:none">
	    	<li>
	    		<label><?php echo $GLOBALS['langUser']['user']; ?></label>
	    		<?php $this->otherUserList->qSelect('nuser','getName()','',($this->otherUserList->rCount()==1)?'':'- selectionner -'); ?>
	    	</li>
			<li>
				<label><?php echo $GLOBALS['langProject']['position']; ?></label>
				<?php
                    $this->positionList->qSelect('nposition');
                ?>
			</li>
			<!-- li class="buttons"><button type="submit" name="invite" value="invite"><?php echo $GLOBALS['langProject']['user_add_button']; ?></button></li -->
	    </ol>
	</div>
    <?php
        }
    ?>
</div>
<?php
	echo '<input type="hidden" name="item" value="'.$this->content->id.'" />';
}
?>
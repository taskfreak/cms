<?php

TznCms::getHeader(true);

// -TODO-TRANSLATE-

?>
<div id="main">
	<div class="box full">
		<h2><?php echo $GLOBALS['langAdminTitle']['modules_enabled']; ?></h2>
		<div class="table boxed hl">
		<?php
		foreach($GLOBALS['objCms']->modules as $key => $mix) {
		?>
			<div id="mod_<?php echo $key; ?>" class="row">
				<div class="col c70"><?php 
					echo ($GLOBALS['langModule'][$key]['name'])?$GLOBALS['langModule'][$key]['name']:$key;
					if ($desc = $GLOBALS['langModule'][$key]['description']) {
						echo ' : <small>'.$desc.'</small>';
					}
				?></div>
				<div class="col c30 action">
					<a href="<?php echo TznCms::getUri('admin/module.php?mode=disable&amp;key='.$key); ?>" onclick="return confirm('<?php echo $GLOBALS['langAdmin']['del_confirm']; ?>')"><?php echo $GLOBALS['langAdmin']['remove']; ?></a>
					<a href="<?php echo TznCms::getUri('admin/module.php?mode=options&amp;key='.$key); ?>" rel="ajaxed"><?php echo $GLOBALS['langAdmin']['setup']; ?></a>
				</div>
			</div>
		<?php
		}
		?>
		</div>
		<div class="footer">
			...
		</div>
	</div>
	<?php
	if (count($this->moduleList->_data)) {
	?>
	<div class="box full">
		<h2><?php echo $GLOBALS['langAdminTitle']['modules_disabled']; ?></h2>
		<div class="table boxed hl">
		<?php
		foreach($this->moduleList->_data as $key => $mix) {
		?>
			<div class="row">
				<div class="col c70"><?php 
					echo ($GLOBALS['langModule'][$key]['name'])?$GLOBALS['langModule'][$key]['name']:$key;
					if ($desc = $GLOBALS['langModule'][$key]['description']) {
						echo ' : <small>'.$desc.'</small>';
					}
				?></div>
				<div class="col c30 action">
					<a href="<?php echo TznCms::getUri('admin/module.php?mode=enable&amp;key='.$key); ?>"><?php echo $GLOBALS['langAdmin']['install']; ?></a>
				</div>
			</div>
		<?php
		}
		?>
		</div>
		<div class="footer">
			...
		</div>
	</div>
	<?php
	}
	?>
</div>
<?php

TznCms::getFooter(true);
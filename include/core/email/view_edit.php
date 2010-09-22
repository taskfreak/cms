<?php

$pTypeFunc = $this->typeFunc;

TznCms::getHeader();

?>
<form action="<?php echo CMS_WWW_URI.'admin/email.php'; ?>" id="squeezed" method="post">
	<div class="frgt"><?php $GLOBALS['objCms']->adminSubmitButtons(); ?></div>
	<h1>Email : <?php echo $GLOBALS['langSystem']['email_legend']; ?></h1>
	<?php 
	$this->item->qHidden('id'); 
	?>
	<ol class="fields">    
        <li>
        	<label><?php echo $GLOBALS['langSystemEmailStuff']['alert']; ?>:</label>
			<strong><?php echo $GLOBALS['langSystemEmail'][$this->item->description]; ?></strong>
		</li>
		<li>
			<label><?php echo $GLOBALS['langSystemEmailStuff']['subject']; ?>:</label>
			<strong><?php echo $this->item->getSubject(); ?></strong>
		</li>
		<li>
			<label><?php echo $this->direction; ?> (<?php echo $GLOBALS['langSystemEmailStuff']['name']; ?>):</label>
			<?php $this->item->qText('recipientName','','wl'); ?>
		</li>
        <li>
        	<label><?php echo $this->direction; ?> (<?php echo $GLOBALS['langSystemEmailStuff']['email']; ?>):</label>
        	<?php $this->item->qText('recipientAddress','','wl'); ?>
        </li>
        <li>
        	<label><?php echo $GLOBALS['langSystemEmailStuff']['cc']; ?> (<?php echo $GLOBALS['langSystemEmailStuff']['email']; ?>):</label>
        	<?php $this->item->qText('recipientCc','','wl'); ?>
        </li>
        <li class="inline">
        	<label><?php 
        	switch ($this->item->html) {
        		case 1:
            		echo 'Message <small>(au format HTML: certaines balises sont autoris&eacute;s)</small>';
            		break;
				case 2:
					echo 'Message <small>(en format mixte (HTML et Texte): certaines balises sont autoris&eacute;s)</small>s';
					break;
				default:
            		echo 'Message <small>(envoy&eacute; au format Texte)</small>';
            		break;
        	}
        	?>:</label>
        	<?php 
        	$this->item->$pTypeFunc('body','','wxxl hl'); 
        	?>
        </li>
        <li class="inline">
        	<span class="frgt"><?php $GLOBALS['objCms']->adminSubmitButtons(); ?></span>
        	<?php $this->item->qCheckBox('active'); ?> <label for="c_active"><?php echo $GLOBALS['langSystemEmailStuff']['enable_label']; ?></label>
        </li>
	</ol>
</form>
<?php

TznCms::getFooter();

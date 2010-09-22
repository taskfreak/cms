<table id="pic_table">
  <tbody>
	<tr>
	<?php
		$i = 1;
		while ($objItem = $this->data->rNext()) {
			$arrSize = getimagesize(TZN_FILE_UPLOAD_PATH.'gallery/'.$objItem->_join->imgfile);
	?>
		<td id="ref<?php echo $i++; ?>"><img src="<?php echo $objItem->_join->getImgUrl('imgfile','',1); ?>" <?php
			echo 'style="width:'.$arrSize[0].'px; height:'.$arrSize[1].'px" ';
			echo 'title="'.$objItem->get('title').'::';
			if ($objItem->body) {
				echo $objItem->get('body');
			} else {
				echo 'by Hoops';
			} 
			echo '"';
			?> class="tomtips" /></td>
	<?php
		}
	?>
		<td class="end">
			<img src="/images/space.png" style="width:500px; height: 480px;" />
		</td>
	</tr>
  </tbody>
</table>
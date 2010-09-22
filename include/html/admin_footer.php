	<hr class="clear butt" />
</div>
<div id="footer">
	<?php
	echo '<small class="flft" style="margin: 12px 0 0 12px">';
	if (is_array($_SESSION['tznReferrers'])) {
		foreach ($_SESSION['tznReferrers'] as $url) {
			if (preg_match('/^\/admin\/([a-z0-9_-]+)\.php\??(.*)$/',$url, $arr)) {
				echo ' &gt; <a href="'.$url.'">';
				if ($arr[2]) {
					preg_match_all('/([^=]+)=([^&]+)&?/',$arr[2], $args);
					$what = $arr[1];
					$idx = array_search('module',$args[1]);
					if ($idx !== false) {
						$what = $args[2][$idx];
					}
					$idx = array_search('action',$args[1]);
					if ($idx !== false) {
						if (in_array('item',$args[1])) {
							echo 'item '.$args[2][$idx];
						} else {
							echo $what.' item '.$args[2][$idx];
						}
					} else if (in_array('id', $args[1])) {
						echo $what.' edit';
					} else {
						echo $what.' list ';
					}
				} else {
					echo $arr[1].' list';
				}
				echo '</a>';
			} else if (preg_match('/\.html/', $url)) {
				echo '<a href="'.$url.'">'.substr($url,1).'</a>';
			} else {
				echo '<a href="'.$url.'">admin</a>';
			}
		}
	} else {
		echo 'No Ref';
	}
	echo '</small>';
	?>
	<p><a href="http://cms.tirzen.com" target="_blank"><img src="<?php echo CMS_WWW_URI; ?>assets/images/logo-cms-mini.png" /> v<?php echo $GLOBALS['objCms']->settings->get('cms_version'); ?></a></p>
</div>
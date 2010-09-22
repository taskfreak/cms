<?php
if ($GLOBALS['objUser']->isLoggedIn()) {
?>
	<div id="menu">
		<ul>
			<li<?php
				if (preg_match('/admin\/index\.php/', $_SERVER['PHP_SELF'])) {
					echo ' class="current"';
				}
			?>><a href="<?php echo TznCms::getUri('admin/'); ?>"><?php echo TznCms::getTranslation('dashboard','langAdminMenuSection'); ?></a></li>
		</ul>
		<?php
		
		// what is the current URI ?
		$cur = $_SERVER['PHP_SELF'];
		if ($_REQUEST['module']) {
			$cur .= '?module='.$_REQUEST['module'];
		}
		
		// list sections
		foreach ($GLOBALS['confAdminMenu'] as $section => $menus) {
		
			echo '<fieldset><legend>'.TznCms::getTranslation($section,'langAdminMenuSection').'</legend><ul>';
			
			// list menus
			foreach ($menus as $menu => $uri) {
			
				// compute regexp
				$reg = $uri;
				if ($_REQUEST['module']) {
					if ($regx = strpos($link,'&')) {
						$reg = substr($link,0,$regx);
					}
				} else if ($regx = strpos($link,'?')) {
					$reg = substr($link,0,$regx);
				}
				$reg = str_replace(array('/','.','?'),array('\/','\.','\?'), $reg);
				
				// 
				
				// display link
				echo '<li';
				if (preg_match('/'.$reg.'$/', $cur)) {
					echo ' class="current"';
				}
				echo '><a href="'.TznCms::getUri($uri).'">'.TznCms::getTranslation($menu,'langAdminMenuItem').'</a></li>';
				
			}
			echo '</ul></fieldset>';
		}
		?>
	</div>
<?php
}
?>
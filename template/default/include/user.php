<?php
	if ($GLOBALS['objUser']->isLoggedIn()) {
?>
			<div id="user" class="pnl">
				<p class="usr"><strong><?php
					echo $GLOBALS['objUser']->getName();
				?></strong></p>
				<ul>
				<?php
				if ($GLOBALS['objUser']->hasAccess(1,'taskfreak')) {
				?>
				<li><a href="/taskfreak.html">taskfreak</a></li>
				<?php 
				}
				?>
				<li><a href="/loguser.php?id=<?php echo $GLOBALS['objUser']->id; ?>">mon compte</a></li>
				<?php
				if ($GLOBALS['objUser']->hasAccess(5)) {
				?>
				<li><a href="/admin/">administration</a></li>
				<?php 
				}
				if ($GLOBALS['objPage']->canEdit()) {
				?>
				<li><a href="/admin/page.php?id=<?php echo $GLOBALS['objPage']->id
					.(($_REQUEST['item'])?('&amp;action=edit&amp;item='.$_REQUEST['item']):''); ?>&amp;backtopage">modifier cette page</a></li>
				<?php 
				}
				?>
				<li><a href="logout.php">d&eacute;connexion</a></li>
				</ul>
			</div>
<?php
	} else {
?>
			<div id="user" class="panel">
				<p id="auth_lnk"><a href="javascript:togglelogin()">identification</a></p>
				<form id="auth_pnl" action="/login.php" method="post"<?php
				if (@constant('TZN_USER_PASS_MODE') == 5) {
					echo ' onsubmit="return cms_secure_login(this);"';
				}
				?> class="fields">
				<ol>
					<li><label for="username">nom d'utilisateur:</label>
					<input id="username" name="username" type="text" /></li>
					<li><label for="password">mot de passe:</label>
					<input id="password" name="password" type="password" /></li>
					<li class="butt">
						<button name="login" value="1" type="submit">login</button>
						<a href="/login.php"><small>&gt;</small> aide</a>
					</li>
				</ol>
				<?php
		        if (@constant('TZN_USER_PASS_MODE') == 5) {
		        	Tzn::qHidden('challenge',$_SESSION['challenge']);
		        }
		        $GLOBALS['objUser']->qLoginTimeZone(); 
		        ?>
				</form>
			</div>
<?php
	}

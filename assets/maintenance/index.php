<?php
ob_implicit_flush(true);

include "miniDB.php";
define('TZN_FILE_SOURCE_PATH','/Users/satane/Sites/parachutisme-bretagne/files/');

?>
<html>
<head>
<title>Parachutisme Bretagne :: Maintenance Base de Donn&eacute;es</title>
<style>
* {
	margin: 0;
	padding: 0;
}
body {
	margin: 10px;
	font-family: sans-serif;
	font-size: normal;
}
h1, h2 {
	margin: 18px 0 9px 0;
} 
p {
	margin: 6px 0 6px 0;
}
ul {
	margin: 0 0 9px 20px;
}
li {
	padding: 2px;
}
hr {
	margin: 12px 0 12px 0;
}
.ok {
	color: #060;
}
.warn {
	color: #999;
}
.error {
	font-weight: bold;
	color: #c00;
}
</style>
</head>
<body>
<h1>Parachutisme Bretagne : Database maintenance</h1>
<hr />
<p><span class="error">Warning</span> : please check file paths before proceeding</p>
<pre><?php 
	echo "Source :\t".TZN_FILE_SOURCE_PATH."\n";
	echo "Destination :\t".TZN_FILE_UPLOAD_PATH;
?></pre>
<hr />
<?php
	if ($_REQUEST['mode']) {
		
		echo '<p>Connecting to database...';
		$db = new MiniDB();
		$db->connect();
		echo '<span class="ok">OK</span></p>';
		
		if (preg_match('/^[a-z_]+$/',$_REQUEST['mode'])) {
			include '_mode_'.$_REQUEST['mode'].'.php';
		}
		
		echo '<hr />';
			
	}
?>
	<ul>
		<li>Import content
			<ul>
				<li><a href="?mode=blog">Blog and Events</a></li>
			</ul>
		</li>
		<li><a href="info.php" target="_blank">php info</a></li>
	</ul>
<script>window.scrollTo(0, document.body.scrollHeight);</script>
</body>
</html>
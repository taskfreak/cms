<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 4.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

/* --- NEWSLETTERS ---------------------------------------------------- */

include $this->filePath('views/include/admin_news_list.php');

/* --- SUBSCRIBERS LIST ----------------------------------------------- */

include $this->filePath('views/include/admin_subscribers_list.php');


/* --- INTRODUCTION --------------------------------------------------- */

?>
<h3 class="acctog">Introduction</h3>
<div class="accinf">
	<?php
		$this->content->qEditArea();
	?>
</div>
<?php

/* --- IMPORT --------------------------------------------------------- */

// include $this->filePath('views/include/admin_import.php');


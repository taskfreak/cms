This is a folder containing the error page templates

It is only called when :
404 : page is not found
405 : page is forbidden
500 : an error occured (SQL, coding, etc...)
maintenance : site is put under maintenance by an administrator

simply create a page named after the error you want to customize

eg. 404.php

each page should at least contain this :

<?php
$pPageTitle = 'Put your page title here';

// include common HTML headers
include CMS_INCLUDE_PATH.'core/header.php';

?>

<!-- HTML content here -->

<?php

// include common HTML footer
include CMS_INCLUDE_PATH.'core/footer.php';

?>
<?php
   // include security image class
   include '_include.php';
   include CMS_CLASS_PATH.'tzn_security.php';
   
   // start PHP session
   session_start();
   
   // get parameters
   isset($_GET['width']) ? $iWidth = (int)$_GET['width'] : $iWidth = 210;
   isset($_GET['height']) ? $iHeight = (int)$_GET['height'] : $iHeight = 40;
   
   // create new image
   $oSecurityImage = new SecurityImage($iWidth, $iHeight);
   if ($oSecurityImage->Create()) {
      // assign corresponding code to session variable 
      // for checking against user entered value
      $_SESSION['cms_security'] = $oSecurityImage->GetCode();
   } else {
      echo 'Image GD library is not installed.';
   }

<?php
   class SecurityImage {
      var $oImage;
      var $iWidth;
      var $iHeight;
      var $iNumChars;
      var $iNumLines;
      var $iSpacing;
      var $sCode;
      
      function SecurityImage($iWidth = 150, $iHeight = 40, $iNumChars = 5, $iNumLines = 30) {
         // get parameters
         $this->iWidth = $iWidth;
         $this->iHeight = $iHeight;
         $this->iNumChars = $iNumChars;
         $this->iNumLines = $iNumLines;
         
         // calculate spacing between characters based on width of image
         $this->iSpacing = (int)($this->iWidth / $this->iNumChars);
      }
      
      function DrawLines() {
         for ($i = 0; $i < $this->iNumLines; $i++) {
            $arrColors = $this->_rdmColors();
            $iLineColour = imagecolorallocate($this->oImage, $arrColors[0], $arrColors[1], $arrColors[2]);
            imageline($this->oImage, rand(0, $this->iWidth), rand(0, $this->iHeight), rand(0, $this->iWidth), rand(0, $this->iHeight), $iLineColour);
         }
      }
      
      function GenerateCode() {
         // reset code
         $this->sCode = '';
         
		 // define stuff
		 $strChars = 'ABDEFGHIKLMPRSTVWXYZ234579';
		 $intLenChars = strlen($strChars);
         // loop through and generate the code letter by letter
         for ($i = 0; $i < $this->iNumChars; $i++) {
            // select random character and add to code string
			$n = mt_rand(1, $intLenChars);
			$this->sCode .= substr($strChars, ($n-1), 1);
         }
      }
      
      function DrawCharacters() {
         // loop through and write out selected number of characters
         for ($i = 0; $i < strlen($this->sCode); $i++) {
            // select random font
            $iCurrentFont = 5; //(rand(0, 1))?3:5;
            
            // select random colour
			$arrColors = $this->_rdmColors(1);
            $iTextColour = imagecolorallocate($this->oImage, $arrColors[0], $arrColors[1], $arrColors[2]);
            
            // write text to image
			$ok = false;
			if (function_exists('imagettftext')) {
				$ok = @imagettftext($this->oImage,14,0, $this->iSpacing / 3 + ($i * $this->iSpacing) - 8,
					($this->iHeight+10) / 2 + rand(-5,5),
					$iTextColour, CMS_INCLUDE_PATH.'7daysrotated.ttf',$this->sCode[$i]);
			}
			if (!$ok) {
				imagestring($this->oImage, $iCurrentFont, $this->iSpacing / 3 + $i * $this->iSpacing,
					($this->iHeight - imagefontheight($iCurrentFont)) / 2 + rand(-5,5), $this->sCode[$i], $iTextColour);
			}
         }
      }
      
      function Create($sFilename = '') {
         // check for existance of GD GIF library
         if (!function_exists('imagepng')) {
            return false;
         }
         
		 // create new image
         $this->oImage = imagecreate($this->iWidth, $this->iHeight);
         
         // allocate white background colour
         imagecolorallocate($this->oImage, 255, 255, 255);

         $this->DrawLines();
         $this->GenerateCode();
         $this->DrawCharacters();
         
         // write out image to file or browser
         if ($sFilename != '') {
            // write stream to file
            imagepng($this->oImage, $sFilename);
         } else {
            // tell browser that data is png
            header('Content-type: image/png');
            
            // write stream to browser
            imagepng($this->oImage);
         }
         
         // free memory used in creating image
         imagedestroy($this->oImage);
         
         return true;
      }
      
      function GetCode() {
         return $this->sCode;
      }

	  function _rdmColors($mode=0) {
			$arrColors = array();
			$idxStrong = rand(0,2);
			for($j=0; $j<3; $j++) {
				if (!$mode) {
					$arrColors[$j] = rand(144,255);
				} else if ($j == $idxStrong) {
					$arrColors[$j] = rand(128,192);
				} else {
					$arrColors[$j] = rand(0,64);
				}
			}
			return $arrColors;
	  }
   }

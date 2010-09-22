<?php

// -TODO-
/*
	- Security : do not allow public to browse or upload
	- Show thumbnails instead of list
	- Add Delete checkboxes
	- Direct upload (low priority)
*/

define('CMS_WWW_URI','/');

include '../../_include.php';

class CmsEditorFile extends TznCollection
{
	
	var $_folder;
	var $_base;
	
	function CmsEditorFile($type='images') {
		parent::TznCollection(null);
		
		$this->_type = ($type == 'images')?'image':'document';
		$this->_folder = TZN_FILE_UPLOAD_PATH.'ckeditor/'.$type.'/';
		$this->_base = TZN_FILE_UPLOAD_URL.'ckeditor/'.$type.'/';
		
	}
	/**
	* Load Module list in Admin section
	* @param str mode filter: installed, uninstalled, all
	*/
	function loadList() {

		$this->_data = $this->_dirs = array();

		if ($handle = opendir($this->_folder)) {

			while (false !== ($file = readdir($handle))) {
				if (is_dir($this->_folder.$file)) {
					if ($file != 'CVS' && $file != '.' && $file != '..') {
						$this->_dirs = $file;
					}
				} else {
					$this->_data[] = $file;
				}
			}
			
			ksort($this->_dirs);
			ksort($this->_data);

			closedir($handle);
			
		}

	}
	
	function getListHtml($func) {
		$html = '';
		if (count($this->_data)) {
			$html = '<ul>';
			foreach ($this->_data as $file) {
				$html .= '<li><a href="javascript:self.close()" onclick="window.opener.CKEDITOR.tools.callFunction('.$func.', \''
					.$this->_base.$file.'\')">'.$file.'</a></li>';
			}
			$html .= '</ul>';
		} else {
			$html = 'No file found';
		}
		return $html;
	}
	
	function upload() {
	
		$objFile = new TznFile($this->_type);
		
		if ($objFile->upload('myfile')) {
		
			copy(TZN_FILE_TEMP_PATH.$objFile->tempName,	$this->_folder.$objFile->origName);
		
			$this->_error = '';
			return $this->_base.$objFile->origName;
		
		} else {
		
			$this->_error = $objFile->_error['myfile'];
			return false;
		
		}
	}
}


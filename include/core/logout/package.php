<?php

class LogoutController extends TznController
{

	function LogoutController() {
		parent::TznController('logout');
	}
	
	function main() {

		$this->userTimeZone = $_SESSION['tznUserTimeZone'];
		$GLOBALS['objUser']->logout();
		
	}

}
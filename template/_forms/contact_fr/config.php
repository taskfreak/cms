<?php

// add referrer to DB automatically if not found
// setting this to false can be a way to slow down spammers
// but you'd then need to create users for each referrer expected
define('PRJ_AUTO_ADD_REFERRER',TRUE);

// accept empty referrers (I suggest you don't do this)
define('PRJ_ACCEPT_EMPTY_REFERRER',FALSE);

// --- Define Form Fields and Rules ----------------------------

class Form extends FormGeneric {
	
	function Form() {
		parent::FormGeneric();
		
		// set form fields
		$this->addProperties(array(
			'email'		=> 'EML',	// email address
			'telephone'	=> 'STR',
			'company'	=> 'STR',
			'type_message'	=> array(
				'demande de devis',
				'proposition commerciale',
				'autre message'
			),
			'message'	=> 'TXT'
		));
		
	}
	
	function check($key) {
		// defines form validation rules
		switch ($key) {
			case 'name':
			case 'email':
			case 'type_demande':
				// field(s) is(are) compulsory
				return $this->checkEmpty($key);
				break;
			default:
				// any other field is fine as it is
				return true;
		}
		return true;
	}
}

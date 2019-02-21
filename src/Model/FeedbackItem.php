<?php

namespace ilateral\SilverStripe\Support\Model;

class FeedbackItem extends DataObject {

	private static $db = array(
		'IsPos' => 'Boolean'
	);
	
	private static $has_one = array(
		'User' => 'Member',
		'Parent' => 'QandAPage'
	);
	
}

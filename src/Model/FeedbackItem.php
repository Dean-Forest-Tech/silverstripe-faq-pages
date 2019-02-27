<?php

namespace ilateral\SilverStripe\Support\Model;

use SilverStripe\ORM\DataObject;

class FeedbackItem extends DataObject 
{
	private static $table_name = "FeedbackItem";

	private static $db = array(
		'IsPos' => 'Boolean'
	);
	
	private static $has_one = array(
		'User' => 'Member',
		'Parent' => 'QandAPage'
	);
	
}

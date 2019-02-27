<?php

namespace ilateral\SilverStripe\Support\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use ilateral\SilverStripe\Support\Model\QandAPage;

class FeedbackItem extends DataObject 
{
	private static $table_name = "FeedbackItem";

	private static $db = array(
		'IsPos' => 'Boolean'
	);
	
	private static $has_one = array(
		'User' => Member::class,
		'Parent' => QandAPage::class
	);
	
}

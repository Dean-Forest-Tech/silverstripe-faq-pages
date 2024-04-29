<?php

namespace DFT\SilverStripe\FAQPages\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;

class FeedbackItem extends DataObject 
{
	private static $table_name = "FeedbackItem";

	private static $db = [
		'IsPos' => 'Boolean'
	];
	
	private static $has_one = [
		'User' => Member::class,
		'Parent' => QandAPage::class
	];
}

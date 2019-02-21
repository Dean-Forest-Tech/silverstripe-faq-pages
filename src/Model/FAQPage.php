<?php

namespace ilateral\SilverStripe\Support\Model;

use Page;

class FAQPage extends Page {
	
	private static $allowed_children = array(
		"QandAPage"
	);

	private static $extensions = array(
		'Lumberjack',
	);

	public function getQuestions()
	{
		return QandAPage::get()->filter('ParentID',$this->ID);
	}

}

<?php

namespace DFT\SilverStripe\FAQPages\Model;

use Page;
use SilverStripe\Lumberjack\Model\Lumberjack;

class FAQPage extends Page 
{
	private static $table_name = 'FAQPage';
		
	private static $allowed_children = array(
		QandAPage::class
	);

	private static $extensions = array(
		Lumberjack::class
	);

	public function getQuestions()
	{
		return QandAPage::get()->filter('ParentID',$this->ID);
	}

}

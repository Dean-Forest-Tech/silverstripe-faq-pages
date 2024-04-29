<?php

namespace DFT\SilverStripe\FAQPages\Model;

use DFT\SilverStripe\FAQPages\Control\FAQPageController;
use Page;
use SilverStripe\Lumberjack\Model\Lumberjack;

class FAQPage extends Page 
{
	private static $table_name = 'FAQPage';

    private static $controller_name = FAQPageController::class;

	private static $allowed_children = [
		QandAPage::class
	];

	private static $extensions = [
		Lumberjack::class
	];

	public function getQuestions()
	{
		return QandAPage::get()
			->filter('ParentID', $this->ID);
	}
}

<?php

class FAQPage extends Page {
	
	private static $allowed_children = array(
		"QandAPage"
	);

	public function getQuestions()
	{
		return QandAPage::get()->filter('ParentID',$this->ID);
	}

}

<?php

namespace DFT\SilverStripe\FAQPages\Model;

use DFT\SilverStripe\FAQPages\Control\QandAPageController;
use Page;
use SilverStripe\Control\Cookie;
use SilverStripe\ORM\HasManyList;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Security\Security;

/**
 * @property int 		 FeedbackScore
 * @method   HasManyList Feedback
 */
class QandAPage extends Page 
{
	private static $table_name = "QandAPage";

    private static $controller_name = QandAPageController::class;

	private static $can_be_root = false;

	private static $show_in_sitetree = false;

	private static $allowed_children = [];

	private static $db = [
		'FeedbackScore' => 'Int'
	];

	private static $has_many = [
		'Feedback' => FeedbackItem::class
	];

	private static $defaults = [
		'ShowInMenus' => false,
		'FeedbackScore' => 0
	];

	public function Link($action = null)
	{
		if ($action) {
			return parent::Link($action);
		} else {
			return Controller::join_links(
				$this->Parent()->Link(), 
				'#'.$this->URLSegment
			);
		}
	}

	public function getCMSFields()
	{
        $fields = parent::getCMSFields();
		
		$fields->addFieldToTab(
			'Root.Main',
			ReadonlyField::create('FeedbackScore')
		);

        $fields->removeByName('PositiveFeedback');
        $fields->removeByName('NegativeFeedback');

        return $fields;
	}

	public function updateFeedbackScore()
	{
		$feedback = $this->Feedback();
		$pos = $feedback->filter('IsPos',true);
		$neg = $feedback->filter('IsPos',false);
		$score = $pos->count() - $neg->count();
		$this->FeedbackScore = $score;
	}

	public function getPosCount()
	{
		$pos = $this->Feedback()->Filter('IsPos',1);
		return $pos->count();
	}

	public function getNegCount() {
		$neg = $this->Feedback()->Filter('IsPos',0);		
		return $neg->count();
	}

	/*
	 * check whether current users feedback is positive or negative
	 * to add styling appropriately
	 */
	public function IsPositive()
	{
		$member = Security::getCurrentUser();
		$member_id = 0;

		if (!empty($member)) {
			$member_id = $member->ID;
		}

		$all_feedback = $this->Feedback();
		$feedback = $all_feedback
			->filter(['UserID' => $member_id, 'IsPos' => 1])
			->first();

		if (!empty($feedback)) {
			return true;
		} else {
			$cookie = Cookie::get('Support.feedback-'.$this->ID);
			$pos = $all_feedback->filter('IsPos', 1);

			if (!empty($cookie) && $pos->exists()) {
				foreach ($pos as $item) {
					if (md5($item->ID) == $cookie) {
						return true;
					}
				}
			}
		}
		return false;	
	}

	public function IsNegative()
	{
		$member = Security::getCurrentUser();
		$member_id = 0;

		if (!empty($member)) {
			$member_id = $member->ID;
		}

		$all_feedback = $this->Feedback();
		$feedback = $all_feedback
			->filter([
				'UserID' => $member_id,
				'IsPos' => 0
			])->first();

		if (!empty($feedback)) {
			return true;
		} else {
			$cookie = Cookie::get('Support.feedback-'.$this->ID);
			$pos = $all_feedback->filter('IsPos', 0);

			if(!empty($cookie) && $pos->exists()) {
				foreach ($pos as $item) {
					if (md5($item->ID) == $cookie) {
						return true;
					}
				}
			}
		}

		return false;	
	}
}

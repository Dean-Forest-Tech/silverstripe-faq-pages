<?php

namespace DFT\SilverStripe\FAQPages\Model;

use Page;
use SilverStripe\Control\Cookie;
use SilverStripe\Security\Member;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\ReadonlyField;
use ilateral\SilverStripe\Support\Model\FeedbackItem;

class QandAPage extends Page 
{
	private static $table_name = "QandAPage";
	
	/**
     * @var bool
     */
	private static $can_be_root = false;
	
	/**
     * @var bool
     */
	private static $show_in_sitetree = false;
	
	private static $allowed_children = array();
	
	private static $db = array(
		'FeedbackScore' => 'Int'
	);

	private static $has_many = array(
		'Feedback' => FeedbackItem::class
	);
	
	private static $defaults = array(
		'ShowInMenus' => false,
		'FeedbackScore' => 0
	);

	public function Link($action = null) {
		if ($action) {
			return parent::Link($action);
		} else {
			return Controller::join_links(
				$this->Parent()->Link(), 
				'#'.$this->URLSegment
			);
		}
	}
	
	public function getCMSFields() {
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
		$pos = $feedback->Filter('IsPos',true);
		$neg = $feedback->Filter('IsPos',false);
		$score = $pos->count() - $neg->count();
		$this->FeedbackScore = $score;
	}
	
	/*
	 * get counts of positive and negative feedback to display in 
	 * template
	 */
	public function getPosCount() {
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
	public function IsPositive() {
		$member = Member::currentUserID();
		$feedback = $this->Feedback();
		if ($feedback->filter(
			array('UserID'=>$member,'IsPos'=>1)
		)->First()) {
			return true;
		} else {
			$cookie = Cookie::get('Support.feedback-'.$this->ID);
			if($cookie && $pos = $feedback->filter('IsPos',1)) {
				foreach ($pos as $item) {
					if (md5($item->ID) == $cookie) {
						return true;
					}
				}
			}
		}
		return false;	
	}
	
	public function IsNegative() {
		$member = Member::currentUserID();
		$feedback = $this->Feedback();
		if ($feedback->filter(
			array('UserID'=>$member,'IsPos'=>0)
		)->First()) {
			return true;
		} else {
			$cookie = Cookie::get('Support.feedback-'.$this->ID);
			if($cookie && $pos = $feedback->filter('IsPos',0)) {
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

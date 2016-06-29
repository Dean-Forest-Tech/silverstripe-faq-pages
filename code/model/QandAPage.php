<?php

class QandAPage extends Page {
	
	private static $show_in_sitetree = false;
	
	private static $allowed_children = array();
	
	private static $has_many = array (
		'Feedback' => 'FeedbackItem'
	);
	
	private static $defaults = array (
		'ShowInMenus' => false,
		'ShowInSearch' => false
	);
	
	public function getCMSFields() {
        $fields = parent::getCMSFields();
        
        $fields->removeByName('PositiveFeedback');
        $fields->removeByName('NegativeFeedback');
        
        return $fields;
	}
	
	public function getFeedbackScore() {
		$pos = $this->Feedback()->Filter('IsPos',1);
		$neg = $this->Feedback()->Filter('IsPos',0);
		$score = $pos->count() - $neg->count();
		return $score;
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
			$cookie = Cookie::get('Support.feedback-'+$this->ID);
			if($pos = $feedback->filter('IsPos',1)) {
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
			$cookie = Cookie::get('Support.feedback-'+$this->ID);
			if($pos = $feedback->filter('IsPos',0)) {
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

<?php

namespace DFT\SilverStripe\FAQPages\Control;

use PageController;
use SilverStripe\Dev\Debug;
use SilverStripe\Control\Cookie;
use SilverStripe\Security\Member;
use ilateral\SilverStripe\Support\Model\QandAPage;
use ilateral\SilverStripe\Support\Model\FeedbackItem;


class QandAPage_Controller extends PageController {
	
	private static $allowed_actions = array(
		'addPositive',
		'addNegative'
	);
	
	/*
	 * check if member has already left feedback
	 * if so set it to positive, otherwise create positive feedback
	 */
	public function addPositive() {
		$member = Member::currentUserID();
		$exists = false;
		$feedback = $this->Feedback();
		// if user is member
		if ($found = $feedback->filter('UserID',$member)->First()) {
			$exists = true;
			$found->IsPos = 1;
			$found->write();
		} else {
			// else use key
			$cookie = Cookie::get('Support.feedback-'.$this->ID);
			if ($cookie) {
				foreach ($feedback as $item) {
					if (md5($feedback->ID) == $cookie) {
						$exists = true;
						$item->IsPos = 1;
						$item->write();
					}
				}
			}
		}

		if ($exists == false) {
			$new_feedback =	FeedbackItem::create(
				array('IsPos' => 1)
			);
			
			$new_feedback->ParentID = $this->ID;
			$new_feedback->UserID = Member::currentUserID();
			$new_feedback->write();
			
			if (!$new_feedback->UserID) {
				$key = md5($new_feedback->ID);
				Cookie::set('Support.feedback-'.$this->ID,$key);
			}
		}

		$record = QandAPage::get()->byID($this->ID);
		$record->writeToStage('Stage');		

		$record->updateFeedbackScore();
		$record->writeToStage('Stage');
		$record->publish("Stage", "Live");
		$record->doPublish();
		Debug::show($record->FeedbackScore);	
		
		return $this->redirect($this->AbsoluteLink());
	}
	
	/*
	 * check if member has already left feedback
	 * if so set it to negative, otherwise create negative feedback
	 */
	public function addNegative() {
		$member = Member::currentUserID();
		$exists = false;
		$feedback = $this->Feedback();
		// if user is member
		if ($found = $feedback->filter('UserID',$member)->First()) {
			$exists = true;
			$found->IsPos = 0;
			$found->write();
		} else {
			// else use key
			$cookie = Cookie::get('Support.feedback-'.$this->ID);
			if ($cookie) {
				foreach ($feedback as $item) {
					if (md5($feedback->ID) == $cookie) {
						$exists = true;
						$item->IsPos = 0;
						$item->write();
					}
				}
			}
		}

		if ($exists == false) {
			$new_feedback =	FeedbackItem::create(
				array('IsPos' => 0)
			);
			
			$new_feedback->ParentID = $this->ID;
			$new_feedback->UserID = Member::currentUserID();
			$new_feedback->write();
			
			if (!$new_feedback->UserID) {
				$key = md5($new_feedback->ID);
				Cookie::set('Support.feedback-'.$this->ID,$key);
			}
		}

		$record = $this->dataRecord;
		$record->write();
		
		$record->updateFeedbackScore();
		$record->writeToStage('Stage');		
		$record->publish("Stage", "Live");		
		$record->doPublish();	
		Debug::show($record->FeedbackScore);	
		
		return $this->redirect($this->AbsoluteLink());
	}
}

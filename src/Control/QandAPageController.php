<?php

namespace DFT\SilverStripe\FAQPages\Control;

use PageController;
use SilverStripe\Control\Cookie;
use SilverStripe\Security\Security;
use DFT\SilverStripe\FAQPages\Model\QandAPage;
use DFT\SilverStripe\FAQPages\Model\FeedbackItem;

class QandAPageController extends PageController
{
	private static $allowed_actions = [
		'addPositive',
		'addNegative'
	];

	public function getDataRecord(): QandAPage
	{
		return $this->dataRecord;
	}

	protected function getCurrentUserID(): int
	{
		$member = Security::getCurrentUser();
		$id = 0;

		if (!empty($member)) {
			$id = $member->ID;
		}

		return $id;
	}

	protected function saveAndPublish(): void
	{
		$record = $this->dataRecord;
		$record->write();

		$record->updateFeedbackScore();
		$record->writeToStage('Stage');
		$record->publish("Stage", "Live");
		$record->doPublish();
	}

	protected function createNewFeedback(bool $positive): FeedbackItem
	{
		$member_id = $this->getCurrentUserID();
		$record = $this->getDataRecord();

		$feedback = FeedbackItem::create();
		$feedback->IsPos = $positive;
		$feedback->ParentID = $this->ID;
		$feedback->UserID = $member_id;
		$feedback->write();

		if (!$feedback->UserID) {
			$key = md5($feedback->ID);
			Cookie::set('Support.feedback-'.$record->ID, $key);
		}

		return $feedback;
	}

	protected function addFeedback(bool $positive): FeedbackItem
	{
		$record = $this->getDataRecord();
		$member_id = $this->getCurrentUserID();
		$cookie = Cookie::get('Support.feedback-' . $record->ID);
		$all_feedback = $record->Feedback();

		/** @var FeedbackItem */
		$feedback = $all_feedback
			->filter('UserID', $member_id)
			->first();

		if (empty($feedback) && !empty($cookie)) {
			foreach ($all_feedback as $item) {
				if (md5($all_feedback->ID) === $cookie) {
					$feedback = $item;
					break;
				}
			}
		}

		if (empty($feedback)) {
			$feedback = $this->createNewFeedback($positive);
		} else {
			$feedback->IsPos = $positive;
			$feedback->write();
		}

		return $feedback;
	}

	public function addPositive()
	{	
		$this->addFeedback(true);
		$this->saveAndPublish();

		return $this->redirect($this->AbsoluteLink());
	}

	public function addNegative()
	{
		$this->addFeedback(false);
		$this->saveAndPublish();

		return $this->redirect($this->AbsoluteLink());
	}
}

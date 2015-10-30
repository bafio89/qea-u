<?php

/**
 * Basically, this Behavior allows a Model to extend its features to verify if
 * a user is authorized to access the Model which attaches the Behavior. 
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class QAuthCheckable extends CActiveRecordBehavior
{
	
	/**
	 * It's useful if you want to define a customized authorization logic.
	 * How to use this method, for example?
	 * 
	 * In Models:
	 * ==========
	 * class Event extends CActiveRecord {
	 * 		public function behaviors()
	 *		{
	 *			return array(
	 *				'QAuthCheckable' => array(
	 *					'class' => 'application.modules.permission.components.QAuthCheckable'
	 *				),
	 *			);
	 * 		}
	 * 		public function isAllowed($user, $rule_name) {
	 * 			// custom logic here
	 * 			return true;
	 * 		}
	 * }
	 * 
	 * In Controllers or elsewhere (e.g: views, widgets, etc):
	 * =======================================================
	 * class EventController extends Controller {
	 * 		public function actionDemo() {
	 * 			$myEvent = new Event();
	 * 			$myEvent->isAllowed($user, 'myrule'); // it returns true or false
	 * 		}
	 * }
	 * 
	 * @param User $user The user object.
	 * @param string $ruleName The rule.
	 * @return boolean True or false, if the user is authorized or not.
	 */
	public function isAllowed($user, $ruleName)
	{
		$ret_val = false;
		
		if (isset($user) && isset($ruleName)) {
			QAuthManager::setCacheEnabled(true);
			
			if (QAuthManager::hasAccess($user, $ruleName, $this->owner->tableName(), $this->owner->id)) {
				$ret_val = true;
			}
		}
		
		return $ret_val;
	}
	
}

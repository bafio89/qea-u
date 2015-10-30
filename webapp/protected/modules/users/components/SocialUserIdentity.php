<?php

/**
 * Customizes the authentication flow by supporting the "social" login.
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class SocialUserIdentity extends BasicUserIdentity
{
	
	/**
	 * 
	 * @var unknown
	 */
	private $_udata = null;
	
	/**
	 * 
	 * @param unknown $user_id
	 */
	function __construct($user_id, $user_data)
	{
		if (isset($user_id) && !empty($user_data)) {
			$user = User::model()->findByPk($user_id);
			
			if (isset($user)) {
				$this->user = $user;
				$this->_udata = $user_data;
			}
		}
	}
	
	/**
	 * Overrides the parent method.
	 *
	 * @return integer Returns the error code.
	 */
	public function authenticate()
	{
		$this->errorCode = self::ERROR_NONE;
		
		if (isset($this->user)) {
			if ($this->user->status == User::STATUS_ACTIVE) {
				if (Yii::app()->user->login($this)) {
					//TODO: write a log here
				}
			}
			else {
				$this->errorCode = self::ERROR_USERNAME_INACTIVE;
			}
		}
		else {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}
		
		return $this->errorCode;
	}
	
}

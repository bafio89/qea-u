<?php

/**
 * This class implements the standard way, used by Yii, to authenticate an user.
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class BasicUserIdentity extends CUserIdentity
{
	
	/**
	 * Defines a custom authentication error, 
	 * thrown when the user account is INACTIVE.
	 * 
	 * @var integer
	 */
	const ERROR_USERNAME_INACTIVE = 3;
	
	/**
	 * The user model retrieved by email during the authentication process.
	 * 
	 * @var User
	 */
	protected $user = null;
	
	/**
	 * Overrides the parent method.
	 * 
	 * @return integer Returns the error code.
	 */
	public function authenticate()
	{
		$this->errorCode = self::ERROR_NONE;
		
		if(isset($this->username) && isset($this->password)) {
			$this->user = User::model()->findByAttributes(
				array(
					'email' => $this->username
				)
			);
			
			if (isset($this->user)) {
				if ($this->user->status == User::STATUS_ACTIVE) {
					if (CPasswordHelper::verifyPassword($this->password, $this->user->password)) {
						Yii::app()->user->login($this);
						//TODO: write a log here
					}
					else {
						$this->errorCode = self::ERROR_PASSWORD_INVALID;
						//TODO: write a log here
					}
				}
				else {
					$this->errorCode = self::ERROR_USERNAME_INACTIVE;
					//TODO: write a log here
				}
			}
			else {
				$this->errorCode = self::ERROR_USERNAME_INVALID;
				//TODO: write a log here
			}
		}
		
		return $this->errorCode;
	}
	
	/**
	 * @return integer Returns the user ID or NULL if the user is not valid.
	 */
	public function getId()
	{
		$ret_val = null;
		
		if (isset($this->user))
			$ret_val = $this->user->id;
		
		return $ret_val;
	}
	
}

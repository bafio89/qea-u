<?php

/**
 * Login form Model.
 *
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class LoginForm extends CFormModel
{
	
	/**
	 * 
	 * @var unknown
	 */
	public $email;
	
	/**
	 * 
	 * @var unknown
	 */
	public $password;
 	
	/**
	 * 
	 * @var unknown
	 */
	private $_identity;
 	
	/**
	 * 
	 * @return multitype:multitype:string
	 */
	public function rules()
	{
		return array(
			array('email, password', 'required'),
			array('email', 'email'),
			array('email, password', 'authenticate'),
		);
	}
 	
	/**
	 * 
	 * @param unknown $attribute
	 * @param unknown $params
	 */
	public function authenticate($attribute, $params)
	{
		$this->_identity = new BasicUserIdentity($this->email, $this->password);
		$this->_identity->authenticate();
		
		/* it's needed to prevent the errors duplication */
		$this->clearErrors();
		
		switch ($this->_identity->errorCode) {
			case BasicUserIdentity::ERROR_USERNAME_INVALID:
				$this->addError('email', Yii::t('UsersModule.login', 'error.email.doesNotExist'));
				break;
			case BasicUserIdentity::ERROR_USERNAME_INACTIVE:
				$this->addError('email', Yii::t('UsersModule.login', 'error.email.isNotActive'));
				break;
			case BasicUserIdentity::ERROR_PASSWORD_INVALID:
				$this->addError('password', Yii::t('UsersModule.login', 'error.password.wrong'));
				break;
		}
	}
	
}

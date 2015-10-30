<?php

/**
 * Reset password form Model.
 *
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class ResetPasswordForm extends CFormModel
{
	
	/**
	 * 
	 * @var unknown
	 */
	public $email;
 
	/**
	 * 
	 * @return multitype:multitype:string
	 */
	public function rules()
	{
		return array(
			array('email', 'required'),
			array('email', 'email'),
			array('email', 'emailExist'),
		);
	}
	
	/**
	 * 
	 * @param unknown $attribute
	 * @param unknown $params
	 */
	public function emailExist($attribute, $params) {
		$user = User::model()->findByAttributes(
			array(
				'email' => $this->email
			)
		);
		
		if (!isset($user))
			$this->addError('email', Yii::t('UsersModule.resetPassword', 'error.email.doesNotExist'));
	}
	
}

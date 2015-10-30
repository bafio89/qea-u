<?php
/**
 * @author Dav Ide <g.davide1989@gmail.com>
 * PasswordRegistrationForm class.
 * PasswordRegistrationForm is the data structure for keeping
 * user password registration form data. 
 * 
 * It is USED by the 'step1' action of 'WizardController' and by 'registration' view.
 */
class PasswordRegistrationForm extends CFormModel
{   
	/**
	 * @var password user password
	 */
	public $password;
	/**
	 * @var confirm_password user password
	 */
	public $confirm_password;
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'password' => Yii::t('RegistrationModule.password', 'page.registration.password'),
				'confirm_password' => Yii::t('RegistrationModule.password', 'page.registration.confirm_password'),
		);
	}
	/**
	 * Declares the validation rules.
	 * The rules state that email is required,
	 * and email needs to be a email.
	 */
	public function rules()
	{
		return array(
			array('password','required'),
 	        array('confirm_password','compare', 'compareAttribute'=>'password'),
		);
	}

}

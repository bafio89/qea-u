<?php
/**
 * @author Dav Ide <g.davide1989@gmail.com>
 * CaptchaRegistrationForm class.
 * CaptchaRegistrationForm is the data structure for keeping
 * captcha data.
 *  
 * It is USED by the 'registration' view and 'basicWizard' view.
 */
class CaptchaRegistrationForm extends CFormModel
{   
	/**
	 * @var verifyCode captcha code
	 */
	public $verifyCode;
	
	public function rules()
	{
		return array(
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
				'verifyCode'=> Yii::t('RegistrationModule.captcha', 'page.registration.captcha.label'),
		);
	}
	
}

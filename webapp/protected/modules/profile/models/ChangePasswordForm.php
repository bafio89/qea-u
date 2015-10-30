<?php

/**
 * 
 * @author Fabio Fiorella
 */
class ChangePasswordForm extends CFormModel
{
	
	/**
	 * 
	 * @var unknown
	 */
	public $oldPassword;	
	
	/**
	 * 
	 * @var unknown
	 */
	public $newPassword;
	
	/**
	 * 
	 * @var unknown
	 */
	public $confirmNewPassword;
	
	/**
	 * 
	 * @return multitype:multitype:string
	 */
	public function rules()
	{
		return array(
			array('oldPassword, newPassword, confirmNewPassword', 'required'),
			array('oldPassword', 'checkOldPassword'),
			array('newPassword', 'compare', 'compareAttribute' =>'confirmNewPassword'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'oldPassword' => Yii::t('ProfileModule.password','page.form.oldPassword'),
			'newPassword' => Yii::t('ProfileModule.password','page.form.newPassword'),
			'confirmNewPassword' => Yii::t('ProfileModule.password','page.form.confirmNewPassword'),
		);
	}
	
	/**
	 * 
	 * @param unknown $attribute
	 * @param unknown $params
	 */
	public function checkOldPassword($attribute, $params)
	{
		$user = User::model()->findByAttributes(array('id' => Yii::app()->user->getId()));
		
		if(!empty($this->oldPassword) && !CPasswordHelper::verifyPassword($this->oldPassword, $user->password)) {
			$this->addError('oldPassword', Yii::t('ProfileModule.password', 'error.password.oldPasswordWrong'));
		}	
	}
	
}

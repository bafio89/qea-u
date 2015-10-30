<?php

/**
 * 
 * @author Dav Ide <g.davide1989@gmail.com>
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class DefaultController extends AbstractRegistrationController
{
	
	/**
	 * 
	 */
	public function actionIndex()
	{
		if (Yii::app()->user->isGuest) {
			/* models */
			$modelUser = new User();
			$modelPersonalInfo = new UserPersonalInfo();
			$modelPassword = new PasswordRegistrationForm();
			$modelCaptcha = new CaptchaRegistrationForm();
			
			/* coming from social login? */
			if (isset(Yii::app()->session['social_email'])) {
				$modelUser = User::model()->findByAttributes(array( // load user by using its email
					'email' => Yii::app()->session['social_email']
				));
			}
			
			/* form sent */
			if (isset($_POST['User'])) {
				$modelUser->attributes = $_POST['User'];
				
				if (isset($_POST['UserPersonalInfo']))			$modelPersonalInfo->attributes	= $_POST['UserPersonalInfo'];
				if (isset($_POST['PasswordRegistrationForm']))	$modelPassword->attributes		= $_POST['PasswordRegistrationForm'];
				if (isset($_POST['CaptchaRegistrationForm']))	$modelCaptcha->attributes		= $_POST['CaptchaRegistrationForm'];
				
				/* validates the form */
				$this->performAjaxValidation(array($modelUser, $modelPersonalInfo, $modelPassword, $modelCaptcha));
				
				/* set password */
				$modelUser->password = $modelPassword->password;
				
				/* perform registration */
				$this->performBasicRegistration($modelUser);
				$this->performPersonalInfoRegistration($modelPersonalInfo);
				
				/* redirect user */
				$this->redirect(
					Yii::app()->createUrl('registration/success')
				);
			}
			else {
				/* set data from social if exists */
				$modelPersonalInfo->first_name	= isset(Yii::app()->session['social_first_name'])	? Yii::app()->session['social_first_name']	: '';
				$modelPersonalInfo->last_name	= isset(Yii::app()->session['social_last_name'])	? Yii::app()->session['social_last_name']	: '';
				$modelPersonalInfo->gender		= isset(Yii::app()->session['social_gender'])		? Yii::app()->session['social_gender']		: '';
				$modelPersonalInfo->birthdate	= isset(Yii::app()->session['social_birthdate'])	? Yii::app()->session['social_birthdate']	: '';
			}
			
			/* render the view */
			$this->render('registration', array(
				'modelUser' => $modelUser,
				'modelPassword' => $modelPassword,
				'modelPersonalInfo' => $modelPersonalInfo,
				'modelCaptcha' => $modelCaptcha,
			));
		}
		else {
			$this->redirect(
				Yii::app()->baseUrl
			);
		}
	}
	
}

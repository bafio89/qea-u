<?php

/**
 * 
 * @author Dav Ide <g.davide1989@gmail.com>
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class WizardController extends AbstractRegistrationController
{

	/**
	 * 
	 */
	public function actionIndex()
	{
		$this->redirect(
			Yii::app()->createUrl('registration/wizard/step1')
		);
	}
	
	/**
	 * 
	 */
	public function actionStep1()
	{
		if (Yii::app()->user->isGuest) {
			if (empty(Yii::app()->session['registration_user_email'])) {
				/* models */
				$modelUser = new User();
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
					
					if (isset($_POST['PasswordRegistrationForm']))	$modelPassword->attributes		= $_POST['PasswordRegistrationForm'];
					if (isset($_POST['CaptchaRegistrationForm']))	$modelCaptcha->attributes		= $_POST['CaptchaRegistrationForm'];
					
					/* validates the form */
					$this->performAjaxValidation(array($modelUser, $modelPassword, $modelCaptcha));
					
					/* set password */
					$modelUser->password = $modelPassword->password;
					
					/* perform registration */
					$this->performBasicRegistration($modelUser);
					
					/* redirect user */
					$this->redirect(
						Yii::app()->createUrl('registration/wizard/step2')
					);
				}
				
				/* render the view */
				$this->render(
					'basicWizard',
					array(
						'model' => $modelUser,
						'partials' => array(
							array(
								'view' => '../_basic',
								'model' => $modelUser,
								'data' => null
							),
							array(
								'view' => '../_password',
								'model' => $modelPassword,
								'data' => null
							),
							array(
								'view' => '../_captcha',
								'model' => $modelCaptcha,
								'data' => null
							),
						)
					)
				);
			}
			else {
				$this->redirect(
					Yii::app()->createUrl('registration/wizard/step2')
				);
			}
		}
		else {
			$this->redirect(
				Yii::app()->baseUrl
			);
		}
	}
	
	/**
	 * 
	 */
	public function actionStep2()
	{
		if (!empty(Yii::app()->session['registration_user_email'])) {
			/* models */
			$modelPersonalInfo = new UserPersonalInfo();
			
			/* form sent */
			if (isset($_POST['UserPersonalInfo'])) {
				$modelPersonalInfo->attributes = $_POST['UserPersonalInfo'];
				
				/* validates the form */
				$this->performAjaxValidation($modelPersonalInfo);
				
				/* perform registration */
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
			$this->render(
				'basicWizard',
				array(
					'model' => $modelPersonalInfo,
					'partials' => array(
						array(
							'view' => '../_personalInfo',
							'model' => $modelPersonalInfo,
							'data' => null
						),
					)
				)
			);
		}
		else {
			$this->redirect(
				Yii::app()->createUrl('registration/wizard/step1')
			);
		}
	}
	
	/**
	 * 
	 * @throws CHttpException
	 */
	public function actionStep3()
	{
		throw new CHttpException(500, 'You should implement this step!');
	}
	
}

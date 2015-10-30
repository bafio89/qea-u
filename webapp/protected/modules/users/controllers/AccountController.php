<?php

Yii::import('application.modules.notifications.components.*');

/**
 * Handles account-related tasks: account activation, password reset.
 *
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class AccountController extends Controller
{
	
	/**
	 * Redirects to home.
	 */
	public function actionIndex()
	{
		$this->redirect(
			Yii::app()->baseUrl
		);
	}
	
	/**
	 * Takes "activation token" as parameter and then activates the account.
	 * 
	 * @param string $token The activation token.
	 */
	public function actionActivate($token)
	{
		$success = false;
		
		if (isset($token)) {
			$meta = User::getMeta(User::METADATA_KEY_ACCOUNT_ACTIVATION_TOKEN, $token);
			
			if (isset($meta)) {
				foreach ($meta as $m) {
					if ($m->user->status == User::STATUS_INACTIVE) {
						$m->user->status = User::STATUS_ACTIVE;
						
						if ($m->user->save()) {
							/* write an INFO syslog */
							// ...
							
							BasicNotifier::sendTemplatedEmail($m->user->email, Yii::t('UsersModule.activate', 'email.subject.done'),
								'users/account_activation_done', null,
								Yii::app()->session['lang']
							);
							
							$success = true;
						}
					}
				}
			}
		}
		
		$this->render(
			'activate',
			array(
				'success' => $success,
			)
		);
	}
	
	/**
	 * Generates a new random password and then send it via email to the user.
	 */
	public function actionResetPassword()
	{
		$model = new ResetPasswordForm();
		$success = false;
		$email = '';
		
		if (isset($_POST['ResetPasswordForm'])) {
			$model->attributes=$_POST['ResetPasswordForm'];
			$this->performAjaxValidation($model);
			
			$user = User::model()->findByAttributes(
				array(
					'email' => $model->email
				)
			);
			
			if (isset($user)) {
				$email = $user->email;
				
				$password_clear = UserHelper::generateRandomPassword();
				$user->password = $user->changeAccountPassword($password_clear);
				
				if ($user->save()) {
					/* write an INFO syslog */
					
					BasicNotifier::sendTemplatedEmail($user->email, Yii::t('UsersModule.resetPassword', 'email.subject'),
						'users/account_reset_password',
						array('{USER_PASSWORD}' => $password_clear),
						Yii::app()->session['lang']
					);
					
					$success = true;
				}
			}
		}
		
		$this->render(
			'resetPassword', 
			array(
				'model' => $model,
				'success' => $success,
				'email' => $email,
			)
		);
	}
	
	/**
	 * Performs an ajax validation.
	 *
	 * @param LoginForm $model
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='password-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}

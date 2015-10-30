<?php

/**
 * 
 * @author Dav Ide <g.davide1989@gmail.com>
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
abstract class AbstractRegistrationController extends Controller
{
	
	/**
	 * 
	 * @var unknown
	 */
	protected $_user;

	/**
	 *
	 * @return multitype:multitype:string number
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the registration page
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
		);
	}
	
	/**
	 * 
	 * @param unknown $model
	 * @return boolean
	 */
	protected function performBasicRegistration($model)
	{
		$output = false;
		
		if (isset($model)) {
			$email = isset($model->email) ? $model->email : null;
			
			if (isset($email)) {
				if (isset(Yii::app()->session['social_email'])) {
					$this->_user = UserHelper::createAccount(
						$email, $model->nickname, $model->password, true, true, true, 
						Yii::app()->session['social_email']
					);
				}
				else {
					$this->_user = UserHelper::createAccount($email, $model->nickname, $model->password);
				}
				
				Yii::app()->session['registration_user_id'] = $this->_user->id;
				Yii::app()->session['registration_user_email'] = $email;
				
				$output = isset($this->_user->id);
			}
		}
		
		return $output;
	}
	
	/**
	 * 
	 * @param unknown $model
	 */
	public function performPersonalInfoRegistration($model)
	{
		$output = false;
		
		if (isset($model)) {
			if (isset(Yii::app()->session['registration_user_id'])) {
				$model->user_id = Yii::app()->session['registration_user_id'];
				
				if ($model->save())
					$output = true;
				
				Yii::app()->session['registration_user_first_name'] = $model->first_name;
				Yii::app()->session['registration_user_last_name'] = $model->last_name;
				Yii::app()->session['registration_user_gender'] = $model->gender;
				Yii::app()->session['registration_user_birthdate'] = $model->birthdate;
			}
		}
		
		return $output;
	}

	/**
	 *
	 */
	public function actionSuccess()
	{
		/* delete session variables */
		$sess_prefix = 'registration_';
		foreach (Yii::app()->session as $k => $v) {
			/* if $k starts with $sess_prefix */
			if (strpos($k, $sess_prefix) === 0) {
				unset(Yii::app()->session[$k]);
			}
		}
		
		/* render the page */
		$this->render('../success');
	}
	
	/**
	 *
	 * @param unknown $model
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax'])&&$_POST['ajax'] === 'registration-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}

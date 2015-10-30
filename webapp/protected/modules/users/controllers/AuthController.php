<?php

/**
 * It's used to authenticate an user.
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class AuthController extends Controller
{
	
	/**
	 * Login view.
	 */
	public function actionIndex()
	{
		if (Yii::app()->user->isGuest) {
			$model = new LoginForm();
			
			$this->render(
				'login',
				array(
					'model' => $model,
				)
			);
		}
		else {
			$this->redirect(
				Yii::app()->baseUrl
			);
		}
	}
	
	/**
	 * Performs basic login.
	 */
	public function actionBasic()
	{
		$model = new LoginForm();
		
		if (isset($_POST['LoginForm'])) {
			$model->attributes=$_POST['LoginForm'];
			$this->performAjaxValidation($model);
			
			$this->redirect(
				Yii::app()->user->returnUrl
			);
		}
	}
	
	/**
	 * Performs social login.
	 * 
	 * @param string $provider The name of the social network.
	 */
	public function actionSocial($provider)
	{
		if (Yii::app()->hasModule('social') && Yii::app()->hasModule('registration')) { /* social and registration modules are loaded */
			
			Yii::import('application.modules.social.models.*');
			Yii::import('application.modules.social.components.*');
			
			try {
				$provider_params = array('redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] . Yii::app()->request->requestUri);
				$p = ProviderManager::getInstance($provider, $provider_params);
				
				if (isset($p)) {
					$result = $p->isAuthorized();
					$user = null;
					
					if ($result['success']) { // app authorized
						if (!isset(Yii::app()->session['registration_user_id'])) {
							if ($result['registration_required']) {
								$user = UserHelper::createAccount($result['user_data']['social_email'], null, null, true, false, false);
	
								$p->setSession($result['user_data']);
								$p->storeOauthData($user->id, $result['user_data']);
							}
							else {
								$user = User::model()->findByPk($result['user_id']);
								
								$identity = new SocialUserIdentity($result['user_id'], $result['user_data']);
								$identity->authenticate();
								
								$p->storeOauthData($result['user_id'], $result['user_data']);
							}
						}
						else {
							$user = User::model()->findByPk(Yii::app()->session['registration_user_id']);
							
							if (isset($user)) {
								$p->setSession($result['user_data']);
								$p->storeOauthData($user->id, $result['user_data']);
							}
						}
						
						$p->pullData( /* = fetch + merge */
							$result['user_data']['social_oauth_id'],
							$result['user_data']['social_oauth_token'],
							$user
						);
						
						if (isset(Yii::app()->session['registration_user_id'])) {
							$this->redirect(
								Yii::app()->createUrl('registration')
							);
						}
						else if ($result['registration_required']) {
							$this->redirect(
								Yii::app()->createUrl('registration')
							);
						}
						else {
							$this->redirect(
								Yii::app()->baseUrl
							);
						}
					}
					else { // app not authorized
						$this->redirect($result['url']);
					}
				}
			}
			catch (Exception $ex) {
				$this->redirect(
					Yii::app()->createUrl('users')
				);
			}
		}
		else { /* modules are not loaded */
			$this->redirect(
				Yii::app()->createUrl('users')
			);
		}
	}
	
	/**
	 * Performs logout.
	 */
	public function actionLogout()
	{
		if (!Yii::app()->user->isGuest) {
			Yii::app()->user->logout();
			$this->render('logout');
		}
		else {
			$this->redirect(
				Yii::app()->baseUrl
			);
		}
	}
	
	/**
	 * Performs an ajax validation.
	 * 
	 * @param LoginForm $model
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}

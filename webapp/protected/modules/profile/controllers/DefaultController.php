<?php

/**
 * 
 * @author Fabio Fiorella
 */
class DefaultController extends Controller
{
	
	/**
	 * 
	 * @return multitype:string
	 */
	public function filters()
	{
		return array(
			'accessControl',
		);
	}
	
	/**
	 * 
	 * @return multitype:multitype:string NULL multitype:string   multitype:string multitype:string   |boolean
	 */
	public function accessRules()
	{
		$params = $this->getActionParams();
		$slug = null;

		if (isset($params['slug']))
			$slug = $params['slug'];

		//rule that show edit profile only to the correct user.
		return array(
			array('allow',
				'actions' => array('edit', 'password', 'delete'),
				'expression' => function() use($slug) {
					$result = false;

					if(isset($slug)) {
						$user = User::model()->findbyAttributes(array('slug'=>$slug, 'status'=>User::STATUS_ACTIVE));

						if(isset($user)) {
							if(Yii::app()->user->getId() == $user->id)
								$result = true;
						}
					}

					return $result;
				},
			),
			array('deny',
				'actions' => array('edit', 'password', 'delete'),
				'users' =>array('*'),
			),
		);
	}
	
	/**
	 * 
	 */
	public function actionIndex()
	{
		if (!Yii::app()->user->isGuest) {
			$user = User::model()->findByPk(Yii::app()->user->getId());
			
			if (isset($user) && isset($user->slug)) {
				$this->redirect(
					Yii::app()->createUrl('profile/view/'.$user->slug)
				);
			}
		}
		
		$this->redirect(
			Yii::app()->baseUrl
		);
	}
	
	/**
	 * 
	 * @param unknown $slug
	 * @throws CHttpException
	 */
	public function actionView($slug)
	{
		$user = null;
		
		if (isset($slug)) {
			$user = User::model()->findbyAttributes(array('slug'=>$slug, 'status'=>User::STATUS_ACTIVE));
			
			if (!isset($user)) {
				throw new CHttpException('404', 
					Yii::t('ProfileModule.profile','error.profile.doesNotExist')
				);
			}
		}

		$this->render('view', array(
			'user' => $user,
			'userPersonalInfo' => $user->personalInfo
		));    
	}
	
	/**
	 * 
	 * @param unknown $slug
	 */
	public function actionEdit($slug)
	{
		$user = User::model()->findbyAttributes(array('slug'=>$slug, 'status'=>User::STATUS_ACTIVE));
		
		if (isset($user)) {
			// save the current mail
			$oldMail  = $user->email;
			
			// if there isn't an instance of UserPersonalInfo we have to create it. 
			if(!isset($user->personalInfo)) {
				$user->personalInfo = new UserPersonalInfo();
			}
	             
			if(isset($_POST['User']) && isset($_POST['UserPersonalInfo'])) {
				$user->attributes=$_POST['User'];
				
				// check if at least one of these field is setted
				if (!empty($_POST['UserPersonalInfo']['first_name']) || !empty($_POST['UserPersonalInfo']['last_name']) || !empty($_POST['UserPersonalInfo']['gender']) || !empty($_POST['UserPersonalInfo']['birthdate'])) {
					$user->personalInfo->attributes = $_POST['UserPersonalInfo'];
					$user->personalInfo->user_id = $user->id;
				}

				// used to check if the user has already changed his own mail
				if($oldMail != $user->email) {
					$user->setScenario('editProfile');
				}
				
				// validation of the models
				$this->performAjaxValidationProfileForm($user);
				
				//verify if the mail was changed             	
				if($oldMail != $user->email) {
					$usersMetadata = new UserMetadata();
					$usersMetadata->user_id = $user->id;
					$usersMetadata->key   = User::METADATA_KEY_EMAIL_CHANGE_NEW_ADDRESS;
					$usersMetadata->value = $user->email;
					$usersMetadata->save();

					$usersMetadata2 = new UserMetadata();
					$usersMetadata2->user_id = $user->id;
					$usersMetadata2->key = User::METADATA_KEY_EMAIL_CHANGE_TOKEN;
					$usersMetadata2->value = UserHelper::generateActivationToken();
					$usersMetadata2->save();

					//confirmation mail
					$activation_link = Yii::app()->createAbsoluteUrl('profile/email?token=' . $usersMetadata2->value);
					BasicNotifier::sendTemplatedEmail($oldMail, Yii::t('ProfileModule.edit','email.subject.changeEmail'), 'profile/change_email_confirmation', array(
						'{CHANGE_EMAIL_ADDRESS}' => $user->email,
						'{CHANGE_EMAIL_CONFIRMATION_LINK}' => $activation_link,
					), Yii::app()->session['lang']);
					
					/* set flash messages */
					Yii::app()->user->setFlash('pending', 'email');

					//assign to as mail of the user his old mail. The new mail before to replace the old one have to be confirmed 
					$user->email = $oldMail;
				}
				
				/* save user and related models */
				$user->withRelated->save(true, array('personalInfo'));
				
				/* set flash messages */
				Yii::app()->user->setFlash('success', true);
				
				/* back to profile */
				$this->redirect(
					Yii::app()->createUrl('profile')
				);
			}
			
			$this->render('edit', array('user'=>$user, 'userPersonalInfo' => $user->personalInfo, 'slug' => $slug));
		}
	}
	
	/**
	 * 
	 * @param unknown $token
	 */
	public function actionEmail($token)
	{
		$success = false;
		
	    $token_validity = UserMetadata::model()->findByAttributes(array(
	    	'key' => User::METADATA_KEY_EMAIL_CHANGE_TOKEN, 
	    	'value' => $token
	    ));
		
		if(isset($token_validity)) {    
			$newEmail = UserMetadata::model()->findByAttributes(array(
				'user_id' => $token_validity->user_id, 
				'key' => User::METADATA_KEY_EMAIL_CHANGE_NEW_ADDRESS
			));

			if(isset($newEmail)) {
				$user = User::model()->findByAttributes(array('id'=>$newEmail->user_id));
				
				if (isset($user)) {
					$user->email = $newEmail->value;
					if ($user->save()) {
						$newEmail->delete();
						$token_validity->delete();
						
						$success = true;
					}
				}
			}
	    }
		
	    $this->render('email', array('success' => $success));
	}
	
	/**
	 * 
	 * @param unknown $slug
	 */
	public function actionPassword($slug)
	{
		$user = User::model()->findbyAttributes(array('slug'=>$slug, 'status'=>User::STATUS_ACTIVE));
		$model = new ChangePasswordForm();
		
		if(isset($_POST['ChangePasswordForm'])) {
			$model->attributes=$_POST['ChangePasswordForm'];
			$this->performAjaxValidationChangePasswordForm($model);
			
			$user->changeAccountPassword($model->newPassword);
			
			if ($user->save()) {
				BasicNotifier::sendTemplatedEmail($user->email, Yii::t('ProfileModule.password', 'email.subject.notification'), 'profile/change_password_notifcation', array(
					'{USER_PASSWORD}' => $model->newPassword,
				), Yii::app()->session['lang']);
				
				/* set flash messages */
				Yii::app()->user->setFlash('success', true);
				Yii::app()->user->setFlash('pending', 'password');
				
				$this->redirect(
					Yii::app()->createUrl('profile')
				);
			}
		}
		
		$this->render('password', array('model'=>$model, 'slug'=>$slug));
	}
	
	/**
	 * 
	 * @param unknown $slug
	 * @param string $proceed
	 */
	public function actionDelete($slug, $proceed = null)
	{
		if (!empty($proceed)) {
			$user = User::model()->findbyAttributes(array('slug'=>$slug, 'status'=>User::STATUS_ACTIVE));
			
			if (isset($user)) {
				$user->status = User::STATUS_DELETED;
				
				if ($user->save()) {
					$this->redirect(
						Yii::app()->createUrl('users/auth/logout')
					);
				}
			}
		}
		
		$this->render('delete');
	}
	
	/**
	 * 
	 * @param unknown $model
	 */
	protected function performAjaxValidationProfileForm($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='profile-form') {
			$valid = $model->withRelated->validate(array('personalInfo'));
			
			if($valid == true) {
				echo json_encode(array());
			}
			else {
				echo $model->withRelated->getJsonErrors(array('personalInfo'));
			}
			
			Yii::app()->end();
		}
	}
	
	/**
	 * 
	 * @param unknown $model
	 */
	protected function performAjaxValidationChangePasswordForm($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='changePwd-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}

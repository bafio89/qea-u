<?php

Yii::import('application.modules.notifications.components.*');

/**
 * This class helps to treat an account.
 * Provides utility methods.
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class UserHelper extends CComponent
{
	
	/**
	 * Create an account on this platform.
	 * 
	 * @param string $email The email address.
	 * @param string $nickname The nickname.
	 * @param string $password The password in clear text (not encrypted).
	 * @param bool $activation_required Specifies if the account activation is needed or not. If yes: generates an activation token. If no: the account will be stored as ACTIVE. 
	 * @param bool $notification Specifies if the user will be notified or not (via mail) about this action.
	 * @param bool $update_account Specifies if the account already exists and then update its data.
	 * @return User Returns the $user object.
	 */
	public static function createAccount($email, $nickname = null, $password = null, $activation_required = true, $notification = true, $update_account = false, $update_old_email = null)
	{
		$user = null;
		
		if (isset($email)) {
			$user = new User();
			
			if ($update_account)
				$user = User::model()->findByAttributes(array('email' => (isset($update_old_email) ? $update_old_email : $email)));
			
			$user->email = $email;
			
			if (!isset($nickname)) {
				$parts = explode('@', $email);
				$user->nickname = $parts[0];
			}
			else {
				$user->nickname = $nickname;
			}
			
			$user->changeAccountPassword(isset($password) ? $password : $password = self::generateRandomPassword());
			$user->status = ($activation_required) ? User::STATUS_INACTIVE : User::STATUS_ACTIVE;
			
			if ($user->save()) {
				// TODO: write a log here
				
				/* created time */
				if (!$update_account)
					$user->addMeta(User::METADATA_KEY_ACCOUNT_CREATED_TIME, date('Y-m-d H:i:s', time()));
				
				/* activation */
				$activation_token = null;
				if ($activation_required) {
					$activation_token = self::generateActivationToken();
					$meta = null;
					
					if ($update_account) {
						$meta = UserMetadata::model()->findByAttributes(array('user_id' => $user->id, 'key' => User::METADATA_KEY_ACCOUNT_ACTIVATION_TOKEN));
					}
					
					if (isset($meta)) {
						$meta->value = $activation_token;
						$meta->save();
					}
					else {
						$user->addMeta(User::METADATA_KEY_ACCOUNT_ACTIVATION_TOKEN, $activation_token);
					}
				}
				
				/* notification */
				if ($notification) {
					BasicNotifier::sendTemplatedEmail($email, Yii::t('UsersModule.create', 'email.subject'), 'users/account_created', array(
						'{USER_EMAIL_ADDRESS}' => $email,
						'{USER_PASSWORD}' => $password,
					), Yii::app()->session['lang']);
					
					if ($activation_required) {
						$activation_link = Yii::app()->createAbsoluteUrl('users/account/activate?token=' . $activation_token);
						
						BasicNotifier::sendTemplatedEmail($email, Yii::t('UsersModule.activate', 'email.subject.required'), 'users/account_activation_required', array(
							'{USER_ACTIVATION_LINK}' => $activation_link,
						), Yii::app()->session['lang']);
					}
				}
			}
		}
		
		return $user;
	}
	
	/**
	 * Generates a random password (allowed symbols: 0-9, A-Z, a-z).
	 * 
	 * @param number $length The lenght of the random string. Default = 8.
	 * @return string Returns the random string.
	 */
	public static function generateRandomPassword($length = 8)
	{
		$symbols  = array_merge(range(0, 9), range('A', 'Z'), range('a', 'z'));
		$rand_str = '';

		for ($i = 0; $i < $length; $i++)
			$rand_str .= $symbols[array_rand($symbols)];

		return $rand_str;
	}
	
	/**
	 * Generates a random activation token (which is a md5 hash).
	 * 
	 * @return string Returns the activation token.
	 */
	public static function generateActivationToken()
	{
		return md5(uniqid());
	}
	
}

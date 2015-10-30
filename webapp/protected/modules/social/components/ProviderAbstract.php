<?php

/**
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
abstract class ProviderAbstract
{

	/**
	 * 
	 * @var unknown
	 */
	protected $params = array();
	
	/**
	 * 
	 * @param unknown $params
	 */
	function __construct($params = array())
	{
		$this->setParams($params);
	}
	
	/**
	 * 
	 * @param unknown $params
	 */
	public function setParams($params = array())
	{
		if (is_array($params))
			$this->params = $params;
	}
	
	/**
	 * 
	 * @return multitype:
	 */
	public function getParams()
	{
		return $this->params;
	}
	
	/**
	 * 
	 * @param unknown $needed
	 * @param string $oauth_id
	 * @param string $oauth_token
	 */
	public abstract function checkScopes($needed, $oauth_id = null, $oauth_token = null);
	
	/**
	 * 
	 * @param unknown $scopes
	 */
	public abstract function isAuthorized($scopes_name = 'scopes');
	
	/**
	 * 
	 * @param unknown $email
	 * @param string $oauth_provider
	 * @param string $oauth_id
	 * @return NULL
	 */
	public function accountExists($email, $oauth_provider = null, $oauth_id = null)
	{
		$found = false;
		$u_id = null;
		
		/* find by oauth info */
		if (isset($oauth_provider) && isset($oauth_id) && !$found) {
			$u = UserOauthAccount::model()->findByAttributes(array(
				'oauth_id' => $oauth_id,
				'oauth_provider' => $oauth_provider
			));
			
			if (isset($u)) {
				$u_id = $u->user->id;
				$found = true;
			}
		}
		
		/* find by email */
		if (isset($email) && !$found) {
			$u = User::model()->findByAttributes(array(
				'email' => $email
			));
			
			if (isset($u)) {
				$u_id = $u->id;
				$found = true;
			}
		}
		
		return $u_id;
	}
	
	/**
	 * 
	 * @param unknown $user
	 */
	public function storeOauthData($user_id, $user_data)
	{
		if (isset($user_id)) {
			$oauth = UserOauthAccount::model()->findByAttributes(array(
				'oauth_id' => $user_data['social_oauth_id'],
				'oauth_provider' => $user_data['social_oauth_provider']
			));
			
			if (!isset($oauth)) {
				$oauth = new UserOauthAccount();
				$oauth->user_id = $user_id;
				$oauth->oauth_provider = $user_data['social_oauth_provider'];
				$oauth->oauth_id = $user_data['social_oauth_id'];
			}

			$oauth->oauth_token = $user_data['social_oauth_token'];
			$oauth->oauth_token_type = $user_data['social_oauth_token_type'];
			$oauth->oauth_token_expires = $user_data['social_oauth_token_expires'];
			$oauth->save();
		}
	}

	/**
	 * Fetches and merges (saves) the user's data.
	 * 
	 * @param unknown $oauth_id
	 * @param unknown $oauth_token
	 * @param string $user
	 */
	public abstract function pullData($oauth_id, $oauth_token, $user = null);
	
	/**
	 * 
	 * @param unknown $user_data
	 */
	public function setSession($user_data)
	{
		foreach ($user_data as $k => $v) {
			Yii::app()->session[$k] = $v;
		}
	}
	
	/**
	 * 
	 */
	public function unsetSession()
	{
		/* prefix used in session's keys */
		$sess_prefix = 'social_';
		
		foreach (Yii::app()->session as $k => $v) {
			/* if $k starts with $sess_prefix */
			if (strpos($k, $sess_prefix) === 0) {
				unset(Yii::app()->session[$k]);
			}
		}
	}
	
}

<?php

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../vendor/google/src');

require_once dirname(__FILE__).'/../vendor/google/src/Google/Client.php';
require_once dirname(__FILE__).'/../vendor/google/src/Google/Service/Plus.php';

/**
 *
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class ProviderGoogle extends ProviderAbstract
{
	
	private $_params = array();
	
	private $_gclient = null;
	private $_gplus = null;
	
	function __construct($provider_params = array())
	{
		if (!empty($provider_params)) {
			$this->_params = $provider_params;
			
			$this->_gclient = new Google_Client();
			$this->_gclient->setClientId(Yii::app()->params['googleApp']['id']);
			$this->_gclient->setClientSecret(Yii::app()->params['googleApp']['secret']);
			$this->_gclient->setDeveloperKey(Yii::app()->params['googleApp']['developerKey']);
			$this->_gclient->setScopes(Yii::app()->params['googleApp']['scopes']);
			
			if ($this->_params['redirect_uri'])
				$this->_gclient->setRedirectUri($this->_params['redirect_uri']);
			
			$this->_gplus = new Google_Service_Plus($this->_gclient);
		}
	}
	
	public function isAuthorized()
	{
		$result['success'] = false;
		
		if (isset($_GET['code'])) {
			$this->_gclient->authenticate($_GET['code']);
			
			var_dump($this->_gclient->getAccessToken());
			exit();
			
			$result['success'] = true;
		}
		else if (isset($_GET['error'])) {
			throw new Exception('We encountered an error.');
		}
		else {
			$result['url'] = $this->_gclient->createAuthUrl();
		}
		
		return $result;
	}
	
}

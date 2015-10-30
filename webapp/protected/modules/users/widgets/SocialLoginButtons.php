<?php

/**
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class SocialLoginButtons extends CWidget
{
	
	/**
	 * Enabled social providers.
	 * 
	 * @var unknown
	 */
	private $_providers = null;
	
	/**
	 * 
	 */
	public function init()
	{
		if (Yii::app()->hasModule('social')) {
			Yii::import('application.modules.social.components.*');
			
			/* enabled providers: */
			$this->_providers = array();
			$this->_providers[] = ProviderManager::FACEBOOK;
// 			$this->_providers[] = ProviderManager::GOOGLE;
// 			$this->_providers[] = ProviderManager::TWITTER;
		}
	}
	
	/**
	 * 
	 */
	public function run()
	{
		if (!empty($this->_providers)) {
			echo $this->render(
				'_socialLoginButtons',
				array(
					'providers' => $this->_providers
				), 
				true
			);
		}
	}
	
}

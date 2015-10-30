<?php

/**
 *
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class ProviderManager
{
	
	/**
	 * 
	 * @var unknown
	 */
	const FACEBOOK = 'facebook';

	/**
	 *
	 * @var unknown
	 */
	const GOOGLE = 'google';
	
	/**
	 * 
	 * @var unknown
	 */
	const TWITTER = 'twitter';
	
	/**
	 * Initializes the social provider.
	 * Usage:
	 * 
	 * <pre>
	 * ProviderManager::getInstance(ProviderManager::FACEBOOK, array('param1'=>true));
	 * </pre>
	 * 
	 * @param unknown $provider_class
	 * @param unknown $provider_params
	 * @throws Exception
	 * @return Ambigous <NULL, ProviderGoogle>
	 */
	public static function getInstance($provider_class, $provider_params = array())
	{
		$provider = null;
		
		switch ($provider_class) {
			case self::FACEBOOK:
				$provider = new ProviderFacebook($provider_params);
				break;
// 			case self::GOOGLE:
// 				$provider = new ProviderGoogle($provider_params);
// 				break;
// 			case self::TWITTER:
// 				$provider = new ProviderTwitter($provider_params);
// 				break;
			
			default:
				$provider = null;
				throw new Exception('Provider not found.');
				break;
		}
		
		return $provider;
	}
	
}

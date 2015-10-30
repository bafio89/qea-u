<?php
class ELangHandler extends CApplicationComponent {
	public $languages = array();
	public $strict = false;
	
	public function init() {
		array_push($this->languages, Yii::app()->getLanguage());
		$this->parseLanguage();
		date_default_timezone_set("UTC");
	}
	
	private function parseLanguage() {
		Yii::app()->urlManager->parseUrl(Yii::app()->getRequest());
		$setCurr = false;
		
		if (is_array(Yii::app()->session['lang']))
			unset(Yii::app()->session['lang']);
		
		// Se la lingua non è definita né via query string né via cookie
		if (!isset($_GET['lang']) && !isset(Yii::app()->request->cookies['lang'])) {
			//echo "<br><br>1";
			$lang = Yii::app()->getRequest()->getPreferredLanguage();
		}
		// Se la lingua è definita SOLO via cookie e non è definita via sessione
		elseif (!isset($_GET['lang']) && isset(Yii::app()->request->cookies['lang']) && !isset(Yii::app()->session['lang'])) {
			//echo "<br><br>2";
			$lang = (string)Yii::app()->request->cookies['lang'];
		}
		// Se la lingua è definita via query string
		elseif (isset($_GET['lang'])) {
			//echo "<br><br>3";
			$lang = $_GET['lang'];
		}
		// Altrimenti tutto è definito nella sessione
		else {
			//echo "<br><br>0";
			$lang = Yii::app()->session['lang'];
		}
		
		// Ricava la lingua
		$longLang  = $lang;
		$shortLang = explode('_', $lang);
		
		if (!in_array($shortLang[0], $this->languages)) {
			$lang = $this->languages[0];
			$longLang  = $lang;
			$shortLang = explode('_', $lang);
		}
		
		if (!isset($shortLang[1]))
			$shortLang[1] = $shortLang[0];
		
		// Imposta la lingua
		ELangHandler::setCookie('lang', $longLang);
		Yii::app()->setLanguage($shortLang[0]);
		setlocale(LC_TIME, $longLang, $longLang.".utf8");
		
		// Imposta valuta e tz
		ELangHandler::setCurrency($shortLang[0], $shortLang[1]);
		ELangHandler::setTimezone($shortLang[0], $shortLang[1]);
		
		//echo "<br><br><br>LN:$longLang / LOC:$longLang.utf8,$act_locale / SH:$shortLang[0]-$shortLang[1] / C:$setCurr";
		//echo "<br>".Yii::app()->session['lang'].' '.Yii::app()->session['curr'].' '.Yii::app()->session['tz'];
	}

	public static function setPreferredLanguage ($value) {
		switch ($value) {
			case 'it' : $lang = 'it_IT'; break;
			case 'en' : $lang = 'en_US'; break;
			default   : $lang = $value; break;
		}
		
		// set language
		$longLang  = $lang;
		$shortLang = explode('_', $value);
		
		ELangHandler::setCookie('lang', $longLang);
		Yii::app()->setLanguage($shortLang[0]);
		setlocale(LC_TIME, $longLang, $longLang.".utf8");
	}
	
	private static function setCookie ($key, $value, $alternative = null) {
		// Imposta il cookie per conservare la variabile per 6 mesi
		Yii::app()->request->cookies[$key] = new CHttpCookie($key, $value,
			array('expire'=>(time()+(180*86400))));
		// Imposta la session per utilizzare da subito nella pagina la variabile
		Yii::app()->session[$key] = $value;
		
		// Per la chiave 'tz' verifica le alternative e imposta il default di sistema
		if ($key == 'tz' && !is_null($alternative)) {
			Yii::app()->session[$key.'_alt'] = $alternative;
			//echo date_default_timezone_get();
		}
	}

	private static function setCurrency($curr_lang, $sublang = null) {
		if (!is_null($sublang) && strtolower($curr_lang) != strtolower($sublang))
			$lang = $curr_lang.'_'.$sublang;
		else
			$lang = $curr_lang;
		$lang = strtolower($lang);
	
		$currency = '';
		$currencySymbol = '';
		switch ($lang) {
			case 'it'   : ; // Italia
			case 'fr'   : ; // Francia
			case 'de'   : ; // Germania
			case 'es'   : ; // Spagna
			case 'pt'   : ; // Portogalllo
			case 'el'   : ; // Grecia
			case 'ga'   : ; // Irlanda
			case 'lb'   : ; // Lussemburgo
			case 'mt'   : ; // Malta
			case 'nl'   : ; // Olanda
			case 'sk'   : ; // Slovacchia
			case 'sl'   : ; // Slovenia
			case 'et'   : ; // Estonia
			case 'fi'   : {
				$currency = 'EUR';
				$currencySymbol = '&euro;';
			} break; // Finlandia
			case 'en_gb': {
				$currency = 'GBP';
				$currencySymbol = '£';
			} break; // Gran Bretagna
			default 	:{
				$currency = 'USD';
				$currencySymbol = '$';
			}break;
		}
		
		ELangHandler::setCookie('curr', $currency);
		ELangHandler::setCookie('curr_symbol', $currencySymbol);
		
		
	}
	
	private static function setTimezone($curr_lang, $sublang = null) {
		if (!is_null($sublang) && strtolower($curr_lang) != strtolower($sublang))
			$lang = $curr_lang.'_'.$sublang;
		else
			$lang = $curr_lang;
		$lang = strtolower($lang);
		
		switch ($lang) {
			case 'it'   : ELangHandler::setCookie('tz', 'Europe/Rome'); break; // Italia (CET/CEST)
			case 'fr'   : ELangHandler::setCookie('tz', 'Europe/Paris'); break; // Francia (CET/CEST)
			case 'de'   : ELangHandler::setCookie('tz', 'Europe/Berlin'); break; // Germania (CET/CEST)
			case 'es'   : ELangHandler::setCookie('tz', 'Europe/Madrid'); break; // Spagna (CET/CEST)
			case 'pt'   : ELangHandler::setCookie('tz', 'Europe/Lisbon'); break; // Portogalllo (WET/WEST)
			case 'el'   : ELangHandler::setCookie('tz', 'Europe/Athens'); break; // Grecia (EET/EEST)
			case 'ga'   : ELangHandler::setCookie('tz', 'Europe/Dublin'); break; // Irlanda (GMT/IST)
			case 'lb'   : ELangHandler::setCookie('tz', 'Europe/Luxembourg'); break; // Lussemburgo (CET/CEST)
			case 'mt'   : ELangHandler::setCookie('tz', 'Europe/Malta'); break; // Malta (CET/CEST)
			case 'nl'   : ELangHandler::setCookie('tz', 'Europe/Amsterdam'); break; // Olanda (CET/CEST)
			case 'sk'   : ELangHandler::setCookie('tz', 'Europe/Bratislava'); break; // Slovacchia (CET/CEST)
			case 'sl'   : ELangHandler::setCookie('tz', 'Europe/Ljubljana'); break; // Slovenia (CET/CEST)
			case 'et'   : ELangHandler::setCookie('tz', 'Europe/Tallinn'); break; // Estonia (EET/no DST)
			case 'fi'   : ELangHandler::setCookie('tz', 'Europe/Helsinki'); break; // Finlandia (EET/EEST)
			case 'en_gb': ELangHandler::setCookie('tz', 'Europe/London'); break; // Gran Bretagna (GMT/BST)
			default     : ELangHandler::setCookie('tz', 'US/Eastern', array('US/Central', 'US/Mountain', 'US/Arizona', 'US/Pacific',
							'US/Alaska', 'US/Hawaii')); break; // Stati Uniti
		}
	}
	
	public static function getCurrencySymbol($currencyAcronym){
		
		$symbol;
		switch ($currencyAcronym) {
			case 'EUR'  : $symbol = '&euro;'; break;
			case 'GBP'  : $symbol = '£'; break;
			case 'USD'  : $symbol = '$'; break;
			default 	: $symbol = '$'; break;
		}
		return $symbol;
	}
	
}
?>

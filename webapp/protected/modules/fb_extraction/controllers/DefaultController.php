<?php

Yii::import('application.models.fbToken');

class DefaultController extends Controller
{
	public function actionIndex()
	{
		
$this->redirect(
		Yii::app()->createUrl("fb_extraction/Fb")
	);
	}
	
	
	public function actionToken(){
		

		$token = new FbToken();
		$pwd = '';
		
		$this->render('tokenForm', array('token' => $token, 'pwd' => $pwd));
		
		
	}
	
	public function actionSaveToken(){
		
		 $token = FbToken::model()->findByPk(1);
		 
		 $app_id = "286043231591201";
		 $app_secret = "4efa214db52acdafb2757124e0d55d9d";
		 
		 if(strcmp($_POST['pwd'], 'bafio8989_') == 0 ){	

		 	$accessToken = $_POST['token'];
		 	
		 	$extended_url = "https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=".$app_id."&client_secret=".$app_secret."&fb_exchange_token=".$accessToken;
		 	
		 	try{
		 		// Crea la risorsa CURL
		 		$ch = curl_init();
		 	
		 		if (FALSE === $ch)
		 			throw new Exception('failed to initialize');
		 	
		 		// Imposta l'URL e altre opzioni
		 		curl_setopt($ch, CURLOPT_URL, $extended_url);
		 		curl_setopt($ch, CURLOPT_HEADER, 0);
		 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		 	
		 		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		 		$output = curl_exec($ch);
		 		$extended_token = str_replace("access_token=", "", $output);
		 		$extended_token = substr($extended_token, 0, strpos($extended_token, "&"));
		 	
		 		if (FALSE === $output)
		 			throw new Exception(curl_error($ch), curl_errno($ch));
		 	
		 	
		 	}catch(Exception $e){
		 		trigger_error(sprintf(
		 		'Curl failed with error #%d: %s',
		 		$e->getCode(), $e->getMessage()),
		 		E_USER_ERROR);
		 	}
		 	$token->token = $extended_token;
			$token->validate();

			$token->save();
		 	
		 }else
		 	echo 'no';
		 
		
		
	}
}
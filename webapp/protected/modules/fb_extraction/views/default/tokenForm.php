<?php 


$cs = Yii::app()->clientScript;
$assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.toastMessage.assets'));
$cs->registerScriptFile($assetsUrl."/jquery.toastmessage.js",CClientScript::POS_HEAD);
$cs->registerScriptFile($assetsUrl."/com.qiris.error.js",CClientScript::POS_HEAD);
$cs->registerCssFile($assetsUrl.'/css/jquery.toastmessage.css');

Yii::import('application.extensions.assets.*');
$form = $this->beginWidget('BsActiveForm', array(
		'id' => 'token-form',
		'layout' => BSHtml::FORM_LAYOUT_VERTICAL,
		'action' => Yii::app()->createUrl('fb_extraction/Default/saveToken'),
		'enableAjaxValidation' => true,
		'enableClientValidation' => false,
		'clientOptions' => array(
				'validateOnSubmit' => true,
				'validateOnType' => false,
				'validateOnChange' => false,
				'afterValidate' => 'js:function(form, data, hasError) { if(hasError) $().qirisError("error", { entity: "TokenForm", formId: "token-form",  errors: data }); else return true; }',
		),
));
?>
	
	
	<textarea name="token" placeholder="token"></textarea><br><br>
	<input name="pwd" placeholder="Pwd"></input><br><br>
	<?php 		
	echo BSHtml::submitButton('Send Degree', array('color' => BSHtml::BUTTON_COLOR_SUCCESS));
				
	$this->endWidget();

?>
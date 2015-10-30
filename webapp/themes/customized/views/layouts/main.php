<?php

/**
 * Setup
 */
$cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;

/**
 * StyleSheets BootStrap
 */
$cs->registerCssFile($themePath . '/assets/bootstrap/css/bootstrap.min.css');
$cs->registerCssFile($themePath . '/assets/bootstrap/css/bootstrap-theme.min.css');

/**
 * StyleSheets FontAwesome
 */
$cs->registerCssFile($themePath . '/assets/fontawesome/css/font-awesome.min.css');

/**
 * JavaScripts
*/
$cs->registerCoreScript('jquery', CClientScript::POS_END);
$cs->registerCoreScript('jquery.ui', CClientScript::POS_END);
$cs->registerScriptFile($themePath . '/assets/bootstrap/js/bootstrap.min.js', CClientScript::POS_END);

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<?php echo $content; ?>
			</div>
		</div>
	</body>
</html>

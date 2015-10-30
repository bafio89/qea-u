<?php
/* @var $this AuthController */
$this->pageTitle = Yii::t('UsersModule.login', 'page.title');

$cs = Yii::app()->clientScript;
$assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.toastMessage.assets'));
$cs->registerScriptFile($assetsUrl."/jquery.toastmessage.js",CClientScript::POS_HEAD);
$cs->registerScriptFile($assetsUrl."/com.qiris.error.js",CClientScript::POS_HEAD);
$cs->registerCssFile($assetsUrl.'/css/jquery.toastmessage.css');
?>
<h1><?php echo Yii::t('UsersModule.login', 'page.title') ?></h1>

<div class="row">
	<div class="col-lg-6">
		<h2><?php echo Yii::t('UsersModule.login', 'page.login.classic.title') ?></h2>
		<p><?php echo Yii::t('UsersModule.login', 'page.login.classic.description') ?></p>
		
		<?php
			$form = $this->beginWidget('BsActiveForm', array(
				'id' => 'login-form',
				'layout' => BSHtml::FORM_LAYOUT_VERTICAL,
				'action' => Yii::app()->createUrl('users/auth/basic'),
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnType' => false,
					'validateOnChange' => false,
					'afterValidate' => 'js:function(form, data, hasError) { if(hasError) $().qirisError("error", { entity: "LoginForm", formId: "login-form",  errors: data }); else return true; }',
				),
			)); 
		?>
		
			<?php
				/* email field */
				echo $form->textFieldControlGroup($model, 'email', array(
					'errorOptions' => array('hideErrorMessage' => true)
				));
				
				/* password field */
				echo $form->passwordFieldControlGroup($model, 'password', array(
					'errorOptions' => array('hideErrorMessage' => true),
				));
			?>
	
			<div class="form-group">
				<?php echo BSHtml::submitButton(Yii::t('UsersModule.login', 'page.login.classic.button'), array('color' => BSHtml::BUTTON_COLOR_SUCCESS)); ?>
				<?php echo Yii::t('UsersModule.login', 'page.login.classic.resetPassword'); ?>
			</div>
	
		<?php
			$this->endWidget();
		?>
	</div>
	<?php if (Yii::app()->hasModule('social')): ?>
		<div class="col-lg-6">
			<h2><?php echo Yii::t('UsersModule.login', 'page.login.social.title') ?></h2>
			<p><?php echo Yii::t('UsersModule.login', 'page.login.social.description') ?></p>
			<p>
				<?php $this->widget('users.widgets.SocialLoginButtons'); ?>
			</p>
		</div>
	<?php endif; ?>
</div>

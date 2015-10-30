<?php
/* @var $this AccountController */
$this->pageTitle = Yii::t('UsersModule.resetPassword', 'page.title');
$this->breadcrumbs = array(
	'Account',
);

$cs = Yii::app()->clientScript;
$assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.toastMessage.assets'));
$cs->registerScriptFile($assetsUrl."/jquery.toastmessage.js",CClientScript::POS_HEAD);
$cs->registerScriptFile($assetsUrl."/com.qiris.error.js",CClientScript::POS_HEAD);
$cs->registerCssFile($assetsUrl.'/css/jquery.toastmessage.css');
?>
<h1><?php echo Yii::t('UsersModule.resetPassword', 'page.title') ?></h1>

<?php if ($success): ?>
	<p><?php echo Yii::t('UsersModule.resetPassword', 'page.resetPassword.success.description') ?></p>
	<div class="alert alert-success">
		<?php echo Yii::t('UsersModule.resetPassword', 'page.resetPassword.success.message', array('{email}'=>$email)) ?>
	</div>
<?php else: ?>
	<p><?php echo Yii::t('UsersModule.resetPassword', 'page.resetPassword.description') ?></p>
	<div>
		<?php
			$form = $this->beginWidget('BsActiveForm', array(
				'id' => 'password-form',
				'layout' => BSHtml::FORM_LAYOUT_VERTICAL,
				'action' => Yii::app()->createUrl('users/account/resetpassword'),
				'enableAjaxValidation' => true,
					'enableClientValidation' => false,
					'clientOptions' => array(
						'validateOnSubmit' => true,
						'validateOnType' => false,
						'validateOnChange' => false,
						'afterValidate' => 'js:function(form, data, hasError) { if(hasError) $().qirisError("error", { entity: "ResetPasswordForm", formId: "password-form",  errors: data }); else return true; }',
					),
			)); 
		?>
			
			<?php 
				echo $form->textFieldControlGroup($model, 'email', array(
					'errorOptions' => array('hideErrorMessage' => true)
				))
			?>
			
			<div class="form-group">
				<?php echo BSHtml::submitButton(Yii::t('UsersModule.resetPassword', 'page.resetPassword.button'), array('color' => BSHtml::BUTTON_COLOR_SUCCESS)); ?>
			</div>
			
		<?php
			$this->endWidget();
		?>
	</div>
<?php endif; ?>

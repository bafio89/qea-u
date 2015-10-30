<?php
$this->pageTitle = Yii::t('ProfileModule.password', 'page.title');

$cs = Yii::app()->clientScript;
$assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.toastMessage.assets'));
$cs->registerScriptFile($assetsUrl."/jquery.toastmessage.js",CClientScript::POS_HEAD);
$cs->registerScriptFile($assetsUrl."/com.qiris.error.js",CClientScript::POS_HEAD);
$cs->registerCssFile($assetsUrl.'/css/jquery.toastmessage.css');
?>

<div class="row">
	<div class="col-lg-6">
		<h1><?php echo Yii::t('ProfileModule.password', 'page.title'); ?></h1>
		<?php
			$form = $this->beginWidget('BsActiveForm', array(
				'id' => 'changePwd-form',
				'action' => Yii::app()->createUrl('profile/password/'. $slug),
				'layout'=>BSHtml::FORM_LAYOUT_VERTICAL,
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnType' => false,
					'validateOnChange' => false,
					'afterValidate' => 'js:function(form, data, hasError) { if(hasError) $().qirisError("error", { entity: "ChangePasswordForm", formId: "changePwd-form",  errors: data }); else return true; }',
				),
			)); 
		?>
		
			<?php
				/* old password field */
				echo $form->passwordFieldControlGroup($model, 'oldPassword', array(
					'errorOptions' => array('hideErrorMessage' => true),
				));

                /* new password field */
				echo $form->passwordFieldControlGroup($model, 'newPassword', array(
					'errorOptions' => array('hideErrorMessage' => true),
				));

                /* confirm new password */
				echo $form->passwordFieldControlGroup($model, 'confirmNewPassword', array(
					'errorOptions' => array('hideErrorMessage' => true),
				));
			?>
			
			<div class="form-group">
				<?php echo BSHtml::submitButton(Yii::t('ProfileModule.password', 'page.form.button'), array('color' => BSHtml::BUTTON_COLOR_SUCCESS)); ?>
			</div>
	
		<?php
			$this->endWidget();
		?>
	</div>
</div>
		
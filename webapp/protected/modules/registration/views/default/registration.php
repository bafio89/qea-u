<?php
$this->pageTitle = Yii::t('RegistrationModule.registration', 'page.title');

$cs = Yii::app()->clientScript;
$assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.toastMessage.assets'));
$cs->registerScriptFile($assetsUrl."/jquery.toastmessage.js",CClientScript::POS_HEAD);
$cs->registerScriptFile($assetsUrl."/com.qiris.error.js",CClientScript::POS_HEAD);
$cs->registerCssFile($assetsUrl.'/css/jquery.toastmessage.css');
?>  

<h1><?php echo Yii::t('RegistrationModule.registration', 'page.title'); ?></h1>		

<div class='row'>
	<div class='col-lg-6'>
		<h2><?php echo Yii::t('RegistrationModule.registration', 'page.registration.classic.title'); ?></h2>
		<p><?php echo Yii::t('RegistrationModule.registration', 'page.registration.classic.description'); ?></p>
		
	    <?php 
			$form = $this->beginWidget('BsActiveForm', array(
				'id' => 'registration-form',
				'layout'=>BSHtml::FORM_LAYOUT_VERTICAL,
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnType' => false,
					'validateOnChange' => false,
					'afterValidate' => 'js:function(form, data, hasError) { if(hasError) $().qirisError("error", { entity: "User", formId: "registration-form", errors: data }); else return true; }',
			)));
		?>
		    
			<h4><?php echo Yii::t('RegistrationModule.basic', 'page.registration.basic.title'); ?></h4>
			<?php
			$this->renderPartial('../_basic',array('form' => $form, 'model' => $modelUser), false, true);
			$this->renderPartial('../_password',array('form' => $form, 'model' => $modelPassword), false, true);
			?>

			<h4><?php echo Yii::t('RegistrationModule.personalInfo', 'page.registration.personalInfo.title'); ?></h4>
			<?php
			$this->renderPartial('../_personalInfo',array('form' => $form, 'model' => $modelPersonalInfo));
			?>

			<?php if (!isset(Yii::app()->session['social_email'])): ?>
				<h4><?php echo Yii::t('RegistrationModule.captcha', 'page.registration.captcha.title'); ?></h4>
				<?php
				$this->renderPartial('../_captcha',array('form' => $form,
					'model'=>$modelCaptcha,
				));
				?>
            <?php endif; ?>

			<div class='form-group'>
				<?php echo BSHtml::submitButton(Yii::t('RegistrationModule.registration', 'page.registration.classic.button'), array('color' => BSHtml::BUTTON_COLOR_SUCCESS)) ?>
			</div>

		<?php
			$this->endWidget();
		?>
	</div>
	<div class='col-lg-6'>
		<?php if (!isset(Yii::app()->session['social_email'])): ?>
			<h2><?php echo Yii::t('RegistrationModule.registration', 'page.registration.social.title') ?></h2>
			<p><?php echo Yii::t('RegistrationModule.registration', 'page.registration.social.description') ?></p>
			<p><?php $this->widget('users.widgets.SocialLoginButtons'); ?></p>
		<?php endif; ?>
	</div>  
</div>

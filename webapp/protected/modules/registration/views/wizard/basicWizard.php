<?php
$this->pageTitle = Yii::t('RegistrationModule.registration', 'page.title');

$cs = Yii::app()->clientScript;
$assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.toastMessage.assets'));
$cs->registerScriptFile($assetsUrl."/jquery.toastmessage.js",CClientScript::POS_HEAD);
$cs->registerScriptFile($assetsUrl."/com.qiris.error.js",CClientScript::POS_HEAD);
$cs->registerCssFile($assetsUrl.'/css/jquery.toastmessage.css');
?>
<h1><?php echo Yii::t('RegistrationModule.registration', 'page.title'); ?></h1>

<div class="row">
	<div class="col-lg-8">
		<div class="row">
			<?php $step_style = 'color: #999;'; ?>
			<div style="<?php echo (Yii::app()->controller->action->id != 'step1') ? $step_style : '' ?>" class='col-lg-4'><h2><?php echo Yii::t('RegistrationModule.basic', 'page.registration.basic.title'); ?></h2></div>
			<div style="<?php echo (Yii::app()->controller->action->id != 'step2') ? $step_style : '' ?>" class='col-lg-4'><h2><?php echo Yii::t('RegistrationModule.personalInfo', 'page.registration.personalInfo.title'); ?></h2></div>
		</div>
		<div class='row'>
			<div class='col-lg-12'>
				<?php 
					$form = $this->beginWidget ( 'BsActiveForm', array (
						'id' => 'registration-form',
						'layout'=>BSHtml::FORM_LAYOUT_VERTICAL,
						'enableAjaxValidation' => true,
						'enableClientValidation' => false,
						'clientOptions' => array (
							'validateOnSubmit' => true,
							'validateOnType' => false,
							'validateOnChange' => false,
							'afterValidate' => 'js:function(form, data, hasError) { if(hasError) $().qirisError("error", { entity: "User", formId: "registration-form", errors: data }); else return true; }',
					)));
				?>
					
					<?php
						foreach ($partials as $p) {
							$this->renderPartial(
								$p['view'], array(
									'form' => $form,
									'model' => $p['model'],
									'data' => (isset($p['data']) ? $p['data'] : array())
								),
							false, false);
						}
					?>
					
					<div class='form-group text-right'>
						<?php echo BSHtml::submitButton(Yii::t('RegistrationModule.wizard', 'page.registration.classic.button.continue'), array('color' => BSHtml::BUTTON_COLOR_SUCCESS)) ?>
					</div>
					
				<?php
					$this->endWidget();
				?>
			</div>
		</div>
	</div>
</div>

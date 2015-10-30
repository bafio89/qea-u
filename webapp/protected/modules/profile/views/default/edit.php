<?php 
$this->pageTitle = Yii::t('ProfileModule.edit', 'page.title');

$cs = Yii::app()->clientScript;
$assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.toastMessage.assets'));
$cs->registerScriptFile($assetsUrl."/jquery.toastmessage.js",CClientScript::POS_HEAD);
$cs->registerScriptFile($assetsUrl."/com.qiris.error.js",CClientScript::POS_HEAD);
$cs->registerCssFile($assetsUrl.'/css/jquery.toastmessage.css');
?>
<div class="row">
	<div class="col-lg-6">
		<?php
			$form = $this->beginWidget('BsActiveForm', array(
				'id' => 'profile-form',
				'action' => Yii::app()->createUrl('profile/edit/'.$slug),
				'layout'=>BSHtml::FORM_LAYOUT_VERTICAL,
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnType' => false,
					'validateOnChange' => false,
					'afterValidate' => 'js:function(form, data, hasError) { if(hasError) $().qirisError("error", { entity: "User", formId: "profile-form",  errors: data }); else return true; }',
				),
			)); 
		?>
		
		<h1><?php echo Yii::t('ProfileModule.edit','page.title') ?></h1>
		
		<h2><?php echo Yii::t('ProfileModule.edit','page.accountInfo.title') ?></h2>
		<?php
			/* email field */
			echo $form->textFieldControlGroup($user, 'email', array(
				'errorOptions' => array('hideErrorMessage' => true)
			));
			
			/* nickname field */
			echo $form->textFieldControlGroup($user, 'nickname', array(
				'errorOptions' => array('hideErrorMessage' => true)
			));
		?>
		<div class="form-group">
			<label class="control-label"><?php echo Yii::t('UsersModule.user','user.password') ?></label>
			<div><a href="<?php echo Yii::app()->createUrl('profile/password/'.$slug) ?>" target="_blank" class="btn btn-default btn-xs"><?php echo Yii::t('ProfileModule.edit','page.accountInfo.password.button') ?></a></div>
		</div>
		
		<h2><?php echo Yii::t('ProfileModule.edit','page.personalInfo.title') ?></h2>
		<?php
			/* first_name field */
			echo $form->textFieldControlGroup($userPersonalInfo, 'first_name', array(
				'errorOptions' => array('hideErrorMessage' => true)
			));
              
			/* last_name field */
			echo $form->textFieldControlGroup($userPersonalInfo, 'last_name', array(
				'errorOptions' => array('hideErrorMessage' => true)
			));
			
			/* gender field */
			echo $form->inlineRadioButtonListControlGroup($userPersonalInfo, 'gender',
				array(
					'F'=>'<i class="fa fa-female" style="font-size: 24px"></i>',
					'M'=>'<i class="fa fa-male" style="font-size: 24px"></i>',
				)
			);
        ?>
		
		<div class='form-group'>
			<label for="birthdate"><?php echo Yii::t('UsersModule.user','user.personalInfo.birthDate') ?></label>
			<?php
				$this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'name'=>'birthdate',
					'model' => $userPersonalInfo,
					'attribute' => 'birthdate',
					'options'=>array(
						'showAnim'=>'show',
						'changeMonth' => true,
						'changeYear' => true,
						'dateFormat' => 'yy-mm-dd',
						'defaultDate' => '1980-01-01',
						'yearRange'  => '1900:'.date('Y'),
					),
					'htmlOptions'=>array(
						'class' => 'form-control',
						'readonly' => 'readonly',
						'style' => 'background-color: #fff; cursor: text;'
					),
				));
			?>
		</div>
		
		<div class="form-group">
			<?php echo BSHtml::submitButton(Yii::t('ProfileModule.edit','page.form.button'), array('color' => BSHtml::BUTTON_COLOR_SUCCESS)); ?>
		</div>
		
		<?php
			$this->endWidget();
		?>
	</div>
</div>
<?php
/**
 * @author Dav Ide <g.davide1989@gmail.com>
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
?>

<?php if (!isset(Yii::app()->session['social_email'])): ?>
	<div class='form-group'>
		<div>
			<?php if(CCaptcha::checkRequirements()): ?>
				<?php echo $form->labelEx($model,'verifyCode'); ?>
				<div class="captcha"><?php $this->widget('CCaptcha'); ?></div>
				<?php 
					echo $form->textField($model,'verifyCode',array(
						'placeholder' => Yii::t('RegistrationModule.captcha', 'page.registration.captcha.label')
					)); 
				?>
				<?php $form->error($model,'verifyCode'); ?>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<?php 
if (!Yii::app()->request->isPostRequest) {
	Yii::app()->clientScript->registerScript(
		'initCaptcha',
		'$(".captcha a").trigger("click");',
		CClientScript::POS_READY
	);
}
?>

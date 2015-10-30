<?php
/** 
 * @author Dav Ide <g.davide1989@gmail.com>
 * @author Niccolò Ciardo <contact@nciardo.com>
 */

/* firstName field */
echo $form->textFieldControlGroup ( $model, 'first_name', array (
	'errorOptions' => array('hideErrorMessage' => true)
));

/* lastName field */
echo $form->textFieldControlGroup ( $model, 'last_name', array (
	'errorOptions' => array('hideErrorMessage' => true) 
));

/* gender field */
echo $form->inlineRadioButtonListControlGroup($model, 'gender',
	array(
		'F'=>'<i class="fa fa-female" style="font-size: 24px"></i>',
		'M'=>'<i class="fa fa-male" style="font-size: 24px"></i>',
	)
);
?>

<div class="form-group">
	<div>
	<label for='birthdate'><?php echo Yii::t('UsersModule.user','user.personalInfo.birthDate') ?></label>
	<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'name'=>'birthdate',
			'model' => $model,
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
</div>

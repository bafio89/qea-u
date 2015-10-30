

<div class="row">
<div class="col-lg-3"></div>

<div class="col-lg-6">
<h2 style="text-align: center">Inserire l'ID del gruppo Facebook che si vuole analizzare</h2><br>



<?php
$cs = Yii::app()->clientScript;
$assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.toastMessage.assets'));
$cs->registerScriptFile($assetsUrl."/jquery.toastmessage.js",CClientScript::POS_HEAD);
$cs->registerScriptFile($assetsUrl."/com.qiris.error.js",CClientScript::POS_HEAD);
$cs->registerCssFile($assetsUrl.'/css/jquery.toastmessage.css');

Yii::import('application.extensions.assets.*');
			$form = $this->beginWidget('BsActiveForm', array(
				'id' => 'fb-form-degree',
				'layout' => BSHtml::FORM_LAYOUT_VERTICAL,
				'action' => Yii::app()->createUrl('fb_extraction/Fb/AddDegreeGroup'),
				'enableAjaxValidation' => true,
				'enableClientValidation' => false,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'validateOnType' => false,
					'validateOnChange' => false,
					'afterValidate' => 'js:function(form, data, hasError) { if(hasError) $().qirisError("error", { entity: "FbFormDegree", formId: "fb-form-degree",  errors: data }); else return true; }',
				),
			)); 
		?>
	
	<?php /* id field */
				echo $form->textFieldControlGroup($degree_group, 'cid', array(
					'errorOptions' => array('hideErrorMessage' => true), 'labelOptions'=>(array('label' => 'ID del corso di Laurea'))
				));
?>
<label id="university"> Università di riferimento</label>
<?php
$this->widget('ext.tokenInput.TokenInput', array(
		'model' => $university,
		'attribute' => 'did',
		'url' => array('../university/default/search'),
		'options' => array(
				'tokenLimit' => '1',
				'hintText'=>false,
				'searchingText'=>'Ricerca in corso...',
				'deleteText'   =>'X',
				'resultsLimit' =>10,
				'allowCreation' => true,
				//'createTokenText'=>'(Aggiungi Specie)',
				//'allowCreation' => true,
				'preventDuplicates' => true,
				'theme'=>'facebook',
				'onAdd' => 'js:function(item){ $.ajax({ url: \'SetSessionVar\', data:\'uid=\'+item.id, type:\'POST\' });}',						
				'resultsFormatter' => 'js:function(item){ return \'<li><p>\' + item.name + \'</p></li>\' }'
				
		
		
		)
));
echo $form->error($university,'did');


?>
<br>
<label> Corso di laurea di riferimento</label>
<?php
$this->widget('ext.tokenInput.TokenInput', array(
		'model' => $degree_group,
		'attribute' => 'degree_id',
		'url' => array('../degree/default/search'),
		'options' => array(
				'tokenLimit' => '1',
				'hintText'=>false,
				'searchingText'=>'Ricerca in corso...',
				'deleteText'   =>'X',
				'allowCreation' => true,
				'resultsLimit' =>10,
				//'createTokenText'=>'(Aggiungi Specie)',
				//'allowCreation' => true,
				'preventDuplicates' => true,
				'theme'=>'facebook',
				'onAdd' => 'js:function(item){ $.ajax({ url: \'setSessionVar\', data:\'did=\'+item.id, type:\'POST\' });}',
				'resultsFormatter' => 'js:function(item){ return \'<li><p>\' + item.name + \'</p></li>\' }'
		
		
		
		)
));
echo $form->error($degree_group,'courses_id');
?>
 

<br>
<label> Esame di riferimento</label>
<?php
$this->widget('ext.tokenInput.TokenInput', array(
		'model' => $exam_group,
		'attribute' => 'courses_id',
		'url' => array('../courses/default/search'),
		'options' => array(
				'tokenLimit' => '1',
				'hintText'=>false,
				'searchingText'=>'Ricerca in corso...',
				'deleteText'   =>'X',
				'resultsLimit' =>10,
				'allowCreation' => true,
				'preventDuplicates' => true,
				'theme'=>'facebook',
				'resultsFormatter' => 'js:function(item){ return \'<li><p>\' + item.name + \'</p></li>\' }'
		
		
		
		)
));
echo $form->error($exam_group,'courses_id');
?>
<br>
<?php 

	 echo BSHtml::submitButton('Send', array('color' => BSHtml::BUTTON_COLOR_SUCCESS));

$this->endWidget();
?>
	
	
	
	

		
		</div>
<div class="col-lg-3"></div>

</div>
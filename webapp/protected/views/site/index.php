<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<h1><?php echo CHtml::encode(Yii::app()->name); ?></h1>
<p>You are <?php echo Yii::app()->user->isGuest ? '' : 'not'?> guest.</p>

<?php 
$widget = $this->beginWidget('ext.toastMessage.toastMessageWidget',array(
	'message'=>$message,
	'type'=>$type,
	'options'=>$options));
$this->endWidget();
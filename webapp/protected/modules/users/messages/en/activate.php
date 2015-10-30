<?php

return array(
	'page.title'=>'Account activation',
	
	'page.activation.success'=>'The account is currently active. <br> Now, you can <a href="'.Yii::app()->createUrl('users/auth').'">sign in</a>.',
	'page.activation.failure'=>'Unfortunately we can\'t activate your account. <br> Please, contact our technical staff to get more info about this issue.',

	'email.subject.required' => 'Activate your account',
	'email.subject.done' => 'Your account is active!',
);

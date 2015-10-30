<?php

return array(
	'page.title'=>'Login',
	
	'page.login.classic.title'=>'Classic',
	'page.login.classic.description'=>'Fill the form with your credentials.',
	'page.login.classic.resetPassword'=>'...or <a href="'.Yii::app()->createUrl('users/account/resetpassword').'" target="_blank">forgotten your password</a>?',
	'page.login.classic.button'=>'Login',
	
	'page.login.social.title'=>'Social',
	'page.login.social.description'=>'Get authenticated in seconds!',
	
	'error.email.doesNotExist'=>'Email doesn\'t exist.',
	'error.email.isNotActive'=>'Account isn\'t active.',
	'error.password.wrong'=>'Password is wrong.',
);

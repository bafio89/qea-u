<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

return CMap::mergeArray(
	require(dirname(__FILE__).'/common.php'),

	array(
		'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		
		'name'=>'QBASE',
		'theme'=>'customized',
        'language'=>'en',

		'aliases' => array(
			'bootstrap' => 'application.modules.bootstrap'
		),
		
		'preload'=>array('log', 'ELangHandler'),
	
		'import'=>array(
			'application.models.*',
			'application.components.*',
			
				
	        'bootstrap.*',
	        'bootstrap.components.*',
	        'bootstrap.models.*',
	        'bootstrap.controllers.*',
	        'bootstrap.helpers.*',
	        'bootstrap.widgets.*',
	        'bootstrap.extensions.*',
		),
	    
			
		'modules'=>array(
			'gii'=>array(
				'generatorPaths' => array(
					'bootstrap.gii'
				),
				'class'=>'system.gii.GiiModule',
				'password'=>'admin',
				'ipFilters'=>array('127.0.0.1','::1'),
			),
			
			'common', 'files', 'groups', 'users', 'registration', 'profile', 'permissions', 'social', 'notifications',
			'fb_extraction','degree','courses','preProcessing','qeaFiller', 'university',
			
			'bootstrap' => array(
				'class' => 'bootstrap.BootStrapModule'
			),
		),	
		'components'=>array(
			'user'=>array(
				'loginUrl'=>array('users/auth'),
				'allowAutoLogin'=>true,
				'autoUpdateFlash' => false,
			),
			'urlManager'=>array(
				'class'=>'application.extensions.langHandler.ELangCUrlManager',
				'urlFormat'=>'path',
				'showScriptName'=>false,
				'rules'=>array(
					/* registration module */
				    'registration/wizard'=>'registration/wizard/index',
					'registration/<action:\w+>'=>'registration/default/<action>',
					
					/* profile module */
					'profile/<action:\w+>/<slug>'=>'profile/default/<action>',
					'profile/<action:\w+>'=>'profile/default/<action>',
					
					/* general purpose */
					'<controller:\w+>/<id:\d+>'=>'<controller>/view',
					'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
					'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				),
			),
			'errorHandler'=>array(
				'errorAction'=>'site/error',
			),
			'log'=>array(
				'class'=>'CLogRouter',
				'routes'=>array(
					array(
						'class'=>'CFileLogRoute',
						'levels'=>'error, warning',
					),
					// uncomment the following to show log messages on web pages
					/*
					array(
						'class'=>'CWebLogRoute',
					),
					*/
				),
			),
			
			'ELangHandler'=>array(
				'class'=>'application.extensions.langHandler.ELangHandler',
				'languages'=>array('en','it'),
				'strict'=>false
			),
			
			'bsHtml' => array(
				'class' => 'bootstrap.components.BSHtml'
			)
		),
	
		// application-level parameters that can be accessed
		// using Yii::app()->params['paramName']
		'params'=>array(
			'EmailNoReply'=>'noreply@example.com',
			'EmailWebmaster'=>'webmaster@example.com',
		),
	)
);
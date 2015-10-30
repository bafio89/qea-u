<?php

return array(
	
	/* db */
	'components'=>array(
		'db'=>array(
			'connectionString'	=> 'mysql:host=localhost;dbname=qeanalysis',
			'emulatePrepare'	=> true,
			'username'			=> 'qbase',
			'password'			=> 'qbase',
			'charset'			=> 'utf8',
		),
	),
	
	/* params */
	'params'=>array(
		'facebookApp'=>array(
			/* app data */
			'id'		=> '616246258438024',
			'secret'	=> '41457aa9902187e85fcc0114b81fa3c9',
			/* permissions */
			'scopes'	=> array('email', 'user_birthday', 'user_location', /* 'publish_stream', */),
		),
		'googleApp'=>array(
			/* app data */
			'id'			=> '386417095741-93hl4b0dv4rb8uipri3l6ha85bnfj454.apps.googleusercontent.com',
			'secret'		=> 'cqIvFiUCKwswEzbz9IHhzOBO',
			'developerKey'	=> 'AIzaSyDecPhBaxqbtL_A8m4Dr2qlmZcelYzSGN8',
			/* permissions */
			'scopes'		=> array(
				'https://www.googleapis.com/auth/plus.me',
				/* 'https://www.googleapis.com/auth/plus.stream.write', 
				'https://www.googleapis.com/auth/plus.media.upload', */
			),
		),
	),

);

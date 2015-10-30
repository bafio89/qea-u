<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/common.php'),
	
	array(
		'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
		'name'=>'QBASE (console)',
	
		'preload'=>array('log'),

		'components'=>array(
			'log'=>array(
				'class'=>'CLogRouter',
				'routes'=>array(
					array(
						'class'=>'CFileLogRoute',
						'levels'=>'error, warning',
					),
				),
			),
		),
	)
);

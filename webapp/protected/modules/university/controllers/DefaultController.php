<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionSearch($q)
	{
	
		$result = array();
	
		$term = trim(addcslashes($q, '%_')); // escape LIKE's special characters
	
		if (!empty($term))
		{
	
			$param = new CDbCriteria( array(
					'condition' => "name LIKE :match",         // no quotes around :match
					'params'    => array(':match' => "%$term%")  // Aha! Wildcards go here
			) );
	
			$cursor = University::model()->findAll( $param );
	
			if (!empty($cursor))
			{
				foreach ($cursor as $id => $value)
				{
					$result[] = array('id' => $value['did'], 'name' => $value['name']);
				}
			}
		}
	
		
		echo json_encode($result);
		Yii::app()->end();
	}
}
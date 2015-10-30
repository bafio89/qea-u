<?php

Yii::import('application.modules.fb_extraction.controllers.FbController');

class GroupCycleController extends Controller
{
	public function actionIndex()
	{
		$this->groupQuery();
		
	}
	
	private function groupQuery(){
		set_time_limit ( 0 );
						
		$exam_group = ExamGroup::model()->findByAttributes(array("update_time" => null));
		
		$exam_groups = array($exam_group);
		
		if(!isset($exam_group)){

			$degree_group = DegreeGroup::model()->findByAttributes(array("update_time" => null));

			if(!isset($degree_group)){
			
				$criteria = new CDbCriteria();			
				$criteria->limit = 3;
				$criteria->order = "update_time ASC";			
				$exam_groups = ExamGroup::model()->findAll($criteria);
			}
		}
					
		$fbController = new FbController();
		
 		foreach ($exam_groups as $eg){
 			if(isset($eg))
 				$fbController->examin_exam_group($eg->eid);
 		}
	
		$degree_groups = array($degree_group);
	
		if(!isset($degree_group)){

			$criteria = new CDbCriteria();
			$criteria->limit = 3;
			$criteria->order = "update_time ASC";
			$degree_groups = DegreeGroup::model()->findAll($criteria);			

		}		

		$fbController = new FbController();
		
		foreach ($degree_groups as $dg){
			if(isset($dg))
				$fbController->examin_degree_group($dg->cid);
		}

		echo 'ENDED';
		
	}
}
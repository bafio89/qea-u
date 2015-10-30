<?php

Yii::import('application.vendors.*');
require_once "qea-new/qa-include/Q2A/Util/Metadata.php";
require_once "qea-new/qa-include/qa-base.php";
require_once "qea-new/qa-include/qa-app-users.php";
require_once "qea-new/qa-include/qa-app-posts.php";
require_once "qea-new/qa-include/app/users-edit.php";
require_once "qea-new/qa-include/app/upload.php";
require_once "qea-new/qa-include/qa-db-users.php";
require_once "qea-new/qa-include/qa-db.php";
require_once "qea-new/qa-include/qa-db-admin.php";
require_once "qea-new/qa-include/app/votes.php";
require_once "qea-new/qa-include/app/cookies.php";
require_once "qea-new/qa-include/app/options.php";

Yii::import('application.modules.fb_extraction.models.FbPost');
Yii::import('application.modules.fb_extraction.models.PhotoFbPost');
Yii::import('application.modules.fb_extraction.models.FbPostComment');
Yii::import('application.modules.fb_extraction.models.LikeFbPost');
Yii::import('application.modules.users.models.Users');
Yii::import('application.modules.fb_extraction.models.DegreeGroup');
Yii::import('application.modules.fb_extraction.models.ExamGroup');
Yii::import('application.modules.degree.models.Degree');
Yii::import('application.modules.courses.models.Courses');
Yii::import('application.modules.university.models.University');


class DefaultController extends Controller
{
	public function actionIndex()
	{
// 		$this->render('index');

// 		$this->actionFakeName();		
		
		$this->actionFillDb();
	}
	
	
	public function actionFillDb(){
		
		set_time_limit ( 0 );
		
		ini_set('mysqli.reconnect', 'on');
		
		ini_set('mysql.connect_timeout', 400);
		
// 		foreach (glob("C:/Users/fabio/git/qea/webapp/protected/vendors/qea/qa-include/*.php") as $filename)
// 		{
// 			inclde $filename;
// 		}				
			$tag = "";
			
			$criteria = new CDbCriteria();

			
			$criteria->condition = "post_type_l1 like '%useful%' AND post_type_l3 like '%si%' ";
			
// 			$criteria->condition = "fbpid = 143370932459708";
			$posts = FbPost::model()->findAll($criteria);
			
			foreach ($posts as $p){
				
				$blob_id = null;
				
				if($this->addFakeName($p->author_id))				
		
					$qa_user_id = $this->addAuthor($p->author_id);
				
				if(is_array($qa_user_id))
						$qa_user_id = $qa_user_id[0];
							
// 				$tag =  str_replace("_baseline", "",$p->post_type_l2);
// 				$tag =  str_replace("_", "-",$p->post_type_l2);
				
				//categories
				if(isset($p->degree_group_id)){
					$category_id = $this->setSecondLevelCategory($p->degree_group_id, 1);
				}else if(isset($p->exam_group_id)){
					
					$exam_group = ExamGroup::model()->findByPk($p->exam_group_id);
					
					$course = Courses::model()->findByPk($exam_group->courses_id);
					
				 	$parent = $this->setSecondLevelCategory($course->degree_id, 0);
					
				 	$category_id = $this->setThirdLevelCategory($course->name, $parent);
				 	
// 				 	$exam_group = ExamGroup::model()->findByPk($p->exam_group_id);
				 	
// 				 	$course = Courses::model()->findByPk($exam_group->courses_id);
				 	
				 	$tag = $course->name;
				 	$tag = str_replace( " ", "-",$tag);
// 				 	$tag = $tag.",";
				}
				
				
				$photo = PhotoFbPost::model()->findByAttributes(array('element_id' => $p->fbpid));
				
							
				if(isset($photo)){
					$photo->medium = str_replace("/".$photo->fid."jpeg", "", $photo->medium);
					$blob_id = qa_upload_file($photo->medium, $photo->fid.'jpeg');
								
				}
			
				$tag_hold = "";
				$substrmessage = substr($p->message, 0, 50);
				if(is_int(strpos($substrmessage, "["))){
					
					for($i = strpos($substrmessage, "[") + 1; $i< strlen($p->message); $i++){
						if($p->message[$i] == "]")
							break;
						$tag_hold = $tag_hold. $p->message[$i];
						
					}
					if($tag == "")				
						$tag = str_replace( " ", "-",$tag_hold);
					else
						$tag = $tag. "," . str_replace( " ", "-",$tag_hold);
				}
							
				
				$title = substr($p->message, 0, 100);
				$title = $title."...";
				

				//posts
				if(qa_post_check_by_fb_id($p->fbpid)== null){
					
					if($blob_id != null){

						$p->message = '<p><img alt="" src="http://www.universitree.com/?qa=blob&amp;qa_blobid='.$blob_id['blobid'].'" style="height:250px;"><br>'.$p->message.'</p>';
	//RIVEDI					
						$question_id = qa_post_create_with_data("Q", null, $title, $p->message, 'html', $p->created_time, $category_id, $tag, $qa_user_id);

						qa_post_set_hidden($question_id, true);
					}else{
						$question_id = qa_post_create_with_data("Q", null, $title, $p->message, '', $p->created_time, $category_id, $tag, $qa_user_id);

					}
					qa_post_create_fb_mapping($question_id, $p->fbpid);
														
					$this->addLikes($p->fbpid, $question_id);
					
					$this->addAnswer($p->fbpid, $question_id);
					
				//	$this->deletePost($p->fbpid);
				}				
				
				
												
// 				$comments = FbPostComment::model()->findAllByAttributes(array("ref_entity_id" => $p->fbpid));
				
// 				foreach ($comments as $c){
// 					if($this->addFakeName($c->author_id))
						
					
// 					if(!$this->addAuthor($c->author_id))
// 						echo "problemi di inconsistenza nei commenti";
// 				}
				
			//inserire qui gli unset	
				unset($blob_id);
				unset($qa_user_id);
				unset($category_id);
				unset($exam_group);
				unset($course);
				unset($photo);
								
				$p->post_type_l1 = "ut_base";
				$p->save();
			}
		
			//$this->deleteUselessPost();
			
		}
	
	
	public function deleteUselessPost(){
		//VERIFICA QUERY
		$connection = Yii::app()->db;
		$command = $connection->createCommand('SELECT photo_fb_post.medium FROM qeanalysis.fb_post, qeanalysis.photo_fb_post where (post_type_l1 like \'%useful%\' OR post_type_l3 like \'%no%\') and fbpid = element_id');
		$row = $command->queryAll();
		
		foreach ($row as $r)
			unlink($r['medium']);
		
			
		$criteria = new CDbCriteria();
			
		$criteria->condition = "post_type_l1 like '%spam%' OR post_type_l3 like '%no%' limit 1";
		
		FbPost::model()->deleteAll($criteria);
			
	}	
		
	public function deletePost($pid){

		$photo = PhotoFbPost::model()->findAllByAttributes(array('element_id' => $pid));
	
		foreach ($photo as $ph) {
	
			if(isset($ph))
				unlink($ph->medium);
		}
		FbPost::model()->deleteByPk($pid);
	}
	
	
	public function addAnswer($pid, $question_id){
		
		$criteria = new CDbCriteria();
			
		$criteria->condition = "ref_entity_id =".$pid." AND message is not null";
		
		$comments = FbPostComment::model()->findAll($criteria);
		
// 		$comments = FbPostComment::model()->findAllByAttributes(array("ref_entity_id" => $pid));
		
		foreach ($comments as $c){
			if($this->addFakeName($c->author_id))
		
					
			$qa_user_id = $this->addAuthor($c->author_id);
			
			if(is_array($qa_user_id))
				$qa_user_id = $qa_user_id[0];

			
			if(qa_post_check_by_fb_id($c->cid) == null){
				$answer_id = qa_post_create_with_data("A", $question_id, null, $c->message, '', $c->created_time, null,null, $qa_user_id);
				
				$this->addFakeLikes($answer_id, $c->like_count, $question_id);
				qa_post_create_fb_mapping($answer_id, $c->cid);
			}
		}
		
		
	}
	
	// devi aggiungere nel db più persone create manualmente così da poter arrivare a più di 14 like nei commenti.
	public function addFakeLikes($answer_id, $n_like, $question_id){
		
			for($i = 2; $i <= $n_like && $i <=27 ;$i++){
				
				$handle = qa_db_user_find_by_id($i);
				
				$post = array("postid" => $answer_id, "userid" => $i, "basetype" => "A", "parentid"=>$question_id);
				qa_vote_set($post, $i, $handle[0], null, 1);
			}
	}
	
	public function addLikes($id, $question_id){
		
		$likes = LikeFbPost::model()->findAllByAttributes(array("ref_entity_id" => $id));
		
		foreach ($likes as $l){
			$this->addFakeName($l->user_id);
			
			$qa_user_id = $this->addAuthor($l->user_id);
		    
			if(is_array($qa_user_id))
				$qa_user_id = $qa_user_id[0];
			
			$user = Users::model()->findByPk($l->user_id);
			$fake_user = FakeUser::model()->findByPk($user->fake_name_id);
			
			$post = array("postid" => $question_id, "userid" => $qa_user_id, "basetype" => "Q");
				qa_vote_set($post, $qa_user_id, $fake_user->first_name.$fake_user->last_name, null, 1);
		}
	}
	
	
	public function setFirstLevelCategory($degree_id){

		$degree = Degree::model()->findByPk($degree_id);
		
		$university = University::model()->findByPk($degree->university_id);
		
		$category_id = qa_db_category_slug_to_id(null, $university->name);
		
		if(!isset($category_id)){
			$category_id = qa_db_category_create(null, $university->name, $university->name);			
		}
		return $category_id;
	}
	
	
	public function setSecondLevelCategory($degree_group_id, $type){		
				
		if($type){

		$degree_group = DegreeGroup::model()->findByPk($degree_group_id);
		
		//$first_level_id = $this->setFirstLevelCategory($degree_group->city);
		$first_level_id = $this->setFirstLevelCategory($degree_group->degree_id);
				
		$degree = Degree::model()->findByPk($degree_group->degree_id);
				
		}else{
			
			$first_level_id = $this->setFirstLevelCategory($degree_group_id);
			
			$degree = Degree::model()->findByPk($degree_group_id);
		}
		$category_id = qa_db_category_slug_to_id($first_level_id, $degree->name);		
		
		if(!isset($category_id)){
			$category_id =  qa_db_category_create($first_level_id, $degree->name, $degree->name);
		}
		return $category_id;
	}
	
	
	public function setThirdLevelCategory($name, $parent){
		
		$category_id = qa_db_category_slug_to_id($parent, $name);
		
		if(!isset($category_id)){
			$category_id = qa_db_category_create($parent, $name, $name);
		}
		
		return $category_id;
	}
	
	public function addAuthor($id){		
		
		$user = Users::model()->findByPk($id);
		$fake_user = FakeUser::model()->findByPk($user->fake_name_id);
		
		if($fake_user != null){
			$fake_user_id =  qa_db_user_find_by_facebook_id($id);
					
			if($fake_user_id == null){
				$fake_user_id = qa_create_new_user($fake_user->email, "ciaociao123", $fake_user->first_name.$fake_user->last_name, QA_USER_LEVEL_BASIC, true);
				qa_db_user_create_by_fb_id($fake_user_id, $id);
			}
		
		}

		return $fake_user_id;
	}
	
	public function addFakeName($id){
// 		
			$connection = Yii::app()->db;
			$user = Users::model()->findByPk($id);
			
			if($user->fake_name_id == null){
				
				$command = $connection->createCommand("SELECT * FROM qeanalysis.fake_user where id not in (SELECT fake_name_id from users where fake_name_id is not null)  order by rand() limit 1");
				
				$row = $command->queryRow();				
				
				$user->fake_name_id = $row['id'];
				$user->save();
								
			}
			
			return true;
			
	}
	
	
	
	
}
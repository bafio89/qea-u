<?php
Yii::import('application.modules.users.*');
Yii::import('application.modules.users.models.UserPersonalInfo');
Yii::import('application.modules.users.models.Users');
Yii::import('application.modules.degree.models.Degree');
Yii::import('application.modules.university.models.University');
Yii::import('application.modules.courses.models.Courses');
Yii::import('application.models.fbToken');

require 'vendor/autoload.php';

	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\GraphUser;
	use Facebook\GraphObject;
	
	$GLOBALS['badWords'] = array();	
	
class FbController extends Controller
{

				
	public function actionIndex()
	{	
		
		$university	  = new University();
		$degree_group = new DegreeGroup();
		$exam_group	  = new ExamGroup();
		
	
		$this->render('fbForm', array('university' => $university,  'degree_group' => $degree_group, 'exam_group' => $exam_group));
		

	}
	
	public function actionSetSessionVar(){
		
		if(isset($_POST['uid'])){
			
		
			$university = new University();
			
			if(!is_numeric($_POST['uid'] )){
			
				$max_id = University::model()->find(array('order' => 'did DESC'));
					
					
				$university->name = $_POST['uid'];
				$university->did = $max_id->did + 1;
					
				$university->validate();
				$university->save();
					
			}else{
				$university->did = $_POST['uid'];
			}
			$_SESSION['uid'] = $university->did;
		}

		if(isset($_POST['did'])){
			$degree = new Degree();
			if(!is_numeric($_POST['did'] )){
					
				$max_id = Degree::model()->find(array('order' => 'did DESC'));
					
				$degree->name = $_POST['did'] ;
				$degree->did = $max_id->did + 1 ;
				$degree->university_id = $_SESSION['uid'];
					
				$degree->validate();
				$degree->save();
			}else{
				$degree->did = $_POST['did'];
			}
			$_SESSION['did'] = $degree->did;
		}
			
	}
	
	
	public function actionResetDb(){
		die();
		LikeFbPost::model()->deleteAll();
		LikeFbFiles::model()->deleteAll();
		LikeFbDoc::model()->deleteAll();
		LikeFbCommentDoc::model()->deleteAll();
		LikeFbCommentFile::model()->deleteAll();
		LikeFbCommentPost::model()->deleteAll();
		
		PhotoFbCommentPost::model()->deleteAll();
		PhotoFbPost::model()->deleteAll();
		
		FbDocComment::model()->deleteAll();
		FbFilesComment::model()->deleteAll();
		FbPostComment::model()->deleteAll();
				
		FbPost::model()->deleteAll();
		FbFiles::model()->deleteAll();
		FbDoc::model()->deleteAll();
		
		MemberDegreeGroup::model()->deleteAll();
		MemberExamGroup::model()->deleteAll();
		
		
	}
	
	
	public function actionAddDegreeGroup(){
		//INSERIRE INFO DI UNIVERSITà E CDL

		if($_POST['ExamGroup']['courses_id'] != ''){
			
			$group = ExamGroup::model()->findByPk($_POST['DegreeGroup']['cid']);
			
		}else{
			$group = DegreeGroup::model()->findByPk($_POST['DegreeGroup']['cid']);
		}
		
		if(!isset($group)){
			
			
			$course = new Courses();
			if($_POST['ExamGroup']['courses_id'] != '' && !is_numeric($_POST['ExamGroup']['courses_id'])){
				
				$max_id = Courses::model()->find(array('order' => 'cid DESC'));
				
				$course->name = $_POST['ExamGroup']['courses_id'];
				$course->degree_id = $_SESSION['did'];
				$course->cid = $max_id->cid + 1;
				
				$course->validate();
				$course->save();
			
			}else{
				$course->cid = $_POST['ExamGroup']['courses_id'];
			}
					
			
			if($_POST['ExamGroup']['courses_id'] != ''){
				$group = new ExamGroup();
				$group->eid = $_POST['DegreeGroup']['cid'];
				$group->courses_id = $course->cid;
				$group->degree_id = $_SESSION['did'];
				
			}else{ 
				$group = new DegreeGroup();
				$group->cid =  $_POST['DegreeGroup']['cid'];
				$group->degree_id = $_SESSION['did'];
			}
		

			$group->validate();
			
			$group->save();
		}
	}
	

	
	public function examin_exam_group($eid){
		

		set_time_limit(0);

		$this->readBadWords();
		


		$app_id = "286043231591201";
		$app_secret = "4efa214db52acdafb2757124e0d55d9d";
		/*$accessToken = "CAAEEJ6E1myEBAPfd1yTv4668Bq2ZB7EQRF9jTJdIiaOfImdl36Lrd4ZA5fQo7N8hyE43tHiviEWGn5cZCRaHSEQ4yOsb6BZB5BvFO7CaFTm9PSyGZCBLr0aZB0DzEo122AHXTdCs4KnUUQopNArLNNudsDEg1sNpOn0cNdhrJJZCG4ebZCOZCUoicYaXOugfxu5XyPnb5fs6HZA8ZAFkVsiQeFA";
		
		$extended_url = "https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=".$app_id."&client_secret=".$app_secret."&fb_exchange_token=".$accessToken;
	*/
		
		$group = ExamGroup::model()->findByPk($eid);
		
		

		/*if(!isset($group)){ 
			
			$group = new ExamGroup();
			$group->eid = $_POST['ExamGroup']['eid'];
			$group->courses_id = $_POST['ExamGroup']['courses_id'];			
		}*/
			
			
// 		$degree_group = DegreeGroup::model()->findByAttributes(array('degree_id' => $group->degree_id));
		

		
		if(isset($group->eid)){

			FacebookSession::setDefaultApplication($app_id, $app_secret);
				
		
 		/*try{
 			// Crea la risorsa CURL
 			$ch = curl_init();
 			
 			if (FALSE === $ch)
 				throw new Exception('failed to initialize');
 			
 			// Imposta l'URL e altre opzioni
 			curl_setopt($ch, CURLOPT_URL, $extended_url);
 			curl_setopt($ch, CURLOPT_HEADER, 0);
 			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 			
 			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
 			$output = curl_exec($ch);
 			$extended_token = str_replace("access_token=", "", $output);
 			$extended_token = substr($extended_token, 0, strpos($extended_token, "&"));

 			if (FALSE === $output)
 				throw new Exception(curl_error($ch), curl_errno($ch));
 			
 			
 		}catch(Exception $e){
 			trigger_error(sprintf(
 			'Curl failed with error #%d: %s',
 			$e->getCode(), $e->getMessage()),
 			E_USER_ERROR);
 		}
 		*/
 		
		$extended_token = FbToken::model()->findByPk(1);
		
 		$session = new FacebookSession($extended_token->token);
		
			$extract_group_info = (new FacebookRequest(
					$session, 'GET', '/'.$group->eid
			))->execute()->getGraphObject(GraphUser::className());
			
 			$this->saveExamGroupInfo($extract_group_info, $group->courses_id);
						
		
 			$limit = 5000;
 			$offset = 0;
 			
			$extract_group_members = (new FacebookRequest(
					$session, 'GET', '/'.$group->eid.'/members?limit='.$limit
			))->execute()->getGraphObject(GraphUser::className());
			
//			$this->saveGroupMember($extract_group_members->getProperty('data'), $group->eid, 'Exam');
					
			while($extract_group_members->getProperty('data')->getProperty(0) != null){
												
				$this->saveGroupMember($extract_group_members->getProperty('data'), $group->eid, 'Exam');
					
				$next = '';
					
				if($extract_group_members->getProperty('paging') != null && $extract_group_members->getProperty('paging')->getProperty('next') != null){
					$next = $extract_group_members->getProperty('paging')->getProperty('next');
				
					$next = str_replace('https://graph.facebook.com/v2.0/', '', $next);
					$next = str_replace('https://graph.facebook.com/v2.1/', '', $next);
				
					$extract_group_members = (new FacebookRequest(
							$session, 'GET', '/'.$next
					))->execute()->getGraphObject(GraphUser::className());
				
				
				}else{
				
					$extract_group_members = null;
				}

// 				$offset = $offset + $limit;
				
// 				$extract_group_members = (new FacebookRequest(
// 						$session, 'GET', '/'.$group->eid.'/members?limit='.$limit.'&offset='.$offset
// 				))->execute()->getGraphObject(GraphUser::className());
				
				
			}
						
			
			$limit = 25;
			$offset = 0;
			if(isset($group->update_time)){
				
				$since = "since=".$group->update_time;
			}else{
				$since = "since=5+years+ago";
			}
					
			$until = date("Y-m-d"). " " .date("H:i:s");
			$until = DateTime::createFromFormat('Y-m-d H:i:s',$until);
			$until->sub(new DateInterval('P3D'));
			$o = new ReflectionObject($until);
			$p = $o->getProperty('date');
			$until = $p->getValue($until);
			echo $until;
			
			
			try{
				$extract_group_feed = (new FacebookRequest(
						$session, 'GET', '/'.$group->eid.'/feed?limit=15000&'.$since.'&until='.$until
				))->execute()->getGraphObject(GraphUser::className());
			} catch (Exception $e){
				
					try{

						$extract_group_feed = (new FacebookRequest(
								$session, 'GET', '/'.$group->eid.'/feed?limit=1000&'.$since.'&until='.$until
						))->execute()->getGraphObject(GraphUser::className());

					} catch(Exception $e){				

						try{

							$extract_group_feed = (new FacebookRequest(
									$session, 'GET', '/'.$group->eid.'/feed?limit=500&'.$since.'&until='.$until
							))->execute()->getGraphObject(GraphUser::className());

						}catch(Exception $e){				
							
								echo "eccezione" ;
							}				


						}				

			}

			while( $extract_group_feed->getProperty('data') != null ){
 				
				$this->saveGroupFeed($extract_group_feed, $group->eid, 'Exam', $session, -1);

				$next = '';
					
				if($extract_group_feed->getProperty('paging') != null && $extract_group_feed->getProperty('paging')->getProperty('next') != null){
					$next = $extract_group_feed->getProperty('paging')->getProperty('next');
				
					$next = str_replace('https://graph.facebook.com/v2.0/', '', $next);
					$next = str_replace('https://graph.facebook.com/v2.1/', '', $next);
				
					$extract_group_feed = (new FacebookRequest(
							$session, 'GET', '/'.$next
					))->execute()->getGraphObject(GraphUser::className());
				
				
				}else{
				
					$extract_group_feed = null;
				}
				
 				
				
// 				$offset = $offset + $limit;
 				
//  				$extract_group_feed = (new FacebookRequest(
//  						$session, 'GET', '/'.$group->eid.'/feed?limit='.$limit.'&offset='.$offset
//  				))->execute()->getGraphObject(GraphUser::className());
 							

			}

			$group = ExamGroup::model()->findByPk($eid);

			$datetime = DateTime::createFromFormat(DateTime::ISO8601,$extract_group_info->getProperty('updated_time'));

			$o = new ReflectionObject($datetime);
			$p = $o->getProperty('date');
			$date = $p->getValue($datetime);
			
			$group->update_time = $date;

			$group->validate();
			$group->save();
			
			///$limit = 25;
		//	$offset = 0;
 			
		/*	$extract_group_docs = (new FacebookRequest(
					$session, 'GET', '/'.$group->eid.'/docs?limit=15000&since=5+years+ago&until=now'
			))->execute()->getGraphObject(GraphUser::className());
			
			while($extract_group_docs->getProperty('data') != null){
				
				$this->saveGroupDocs($extract_group_docs, $group->eid,'Exam');				
				
				$next = '';
					
				if($extract_group_docs->getProperty('paging') != null &&  $extract_group_docs->getProperty('paging')->getProperty('next') != null){
					$next = $extract_group_docs->getProperty('paging')->getProperty('next');
				
					$next = str_replace('https://graph.facebook.com/v2.0/', '', $next);
					$next = str_replace('https://graph.facebook.com/v2.1/', '', $next);
				
					$extract_group_docs = (new FacebookRequest(
							$session, 'GET', '/'.$next
					))->execute()->getGraphObject(GraphUser::className());
				
				
				}else{
				
					$extract_group_feed = null;
				}
				
// 				$offset = $offset + $limit;
				
// 				$extract_group_docs = (new FacebookRequest(
// 						$session, 'GET', '/'.$group->eid.'/docs?limit=15000&since=5+years+ago&until=now'
// 				))->execute()->getGraphObject(GraphUser::className());
			}

			*/
			
			/*$limit = 25;
			$offset = 0;
			
			$extract_group_files = (new FacebookRequest(
					$session, 'GET', '/'.$group->eid.'/files?limit=15000&since=5+years+ago&until=now'
			))->execute()->getGraphObject(GraphUser::className());
			
			while($extract_group_files != null && $extract_group_files->getProperty('data') != null){
				$this->saveGroupFiles($extract_group_files, $group->eid, 'Exam');
							
				$next = '';
					
				if($extract_group_files->getProperty('paging') != null && $extract_group_files->getProperty('paging')->getProperty('next') != null ){
					$next = $extract_group_files->getProperty('paging')->getProperty('next');
				
					$next = str_replace('https://graph.facebook.com/v2.0/', '', $next);
					$next = str_replace('https://graph.facebook.com/v2.1/', '', $next);
					
					$extract_group_files = (new FacebookRequest(
							$session, 'GET', '/'.$next
					))->execute()->getGraphObject(GraphUser::className());
				
				
				}else{
				
					$extract_group_files = null;
				}
				
// 				$offset = $offset + $limit;
				
// 				$extract_group_files = (new FacebookRequest(
// 						$session, 'GET', '/'.$group->eid.'/files?limit='.$limit.'&offset='.$offset
// 				))->execute()->getGraphObject(GraphUser::className());
			}
				*/		
		}
		
		$this->performAjaxValidation($group);
	//	$this->render('results', array('group'=> $group));
		
	}
	
	public function examin_degree_group($cid){
			
		set_time_limit(0);
		
		$this->readBadWords();
		
		$group = DegreeGroup::model()->findByPk($cid);
		
	   /*if(!isset($group)){
			$group = new DegreeGroup();
			$group->cid = $_POST['DegreeGroup']['cid'];
			$group->degree_id = $_POST['DegreeGroup']['degree_id'];
		}*/
		
		// 		$degree_group = DegreeGroup::model()->findByAttributes(array('degree_id' => $group->degree_id));
		
		if(isset($group->cid)){
			FacebookSession::enableAppSecretProof(false);
			FacebookSession::setDefaultApplication('286043231591201', '4efa214db52acdafb2757124e0d55d9d');
			
			$extended_token = FbToken::model()->findByPk(1);
			
			$session = new FacebookSession($extended_token->token);
					
			$extract_group_info = (new FacebookRequest(
					$session, 'GET', '/'.$group->cid
			))->execute()->getGraphObject(GraphUser::className());
				
			$this->saveDegreeGroupInfo($extract_group_info, $group->degree_id);
					

			$limit = 25;
			$offset = 0;
			 
			$extract_group_members = (new FacebookRequest(
					$session, 'GET', '/'.$group->cid.'/members?limit='.$limit.'&offset='.$offset
			))->execute()->getGraphObject(GraphUser::className());
						

			while($extract_group_members->getProperty('data')->getProperty(0) != null){
									
					$this->saveGroupMember($extract_group_members->getProperty('data'), $group->cid, 'Degree');
					
					$next = '';
						
					if($extract_group_members->getProperty('paging') != null && $extract_group_members->getProperty('paging')->getProperty('next') != null){
						$next = $extract_group_members->getProperty('paging')->getProperty('next');
					
						$next = str_replace('https://graph.facebook.com/v2.0/', '', $next);
						$next = str_replace('https://graph.facebook.com/v2.1/', '', $next);
					
						$extract_group_members = (new FacebookRequest(
								$session, 'GET', '/'.$next
						))->execute()->getGraphObject(GraphUser::className());
					
					
					}else{
					
						$extract_group_members = null;
					}
					
					
// 					$offset = $offset + $limit; 
					
// 					$extract_group_members = (new FacebookRequest(
// 							$session, 'GET', '/'.$group->cid.'/members?limit='.$limit.'&offset='.$offset
// 					))->execute()->getGraphObject(GraphUser::className());
				
			}
			
		
		$limit = 25;
		$offset = 0;
		if(isset($group->update_time)){
			$since = "since=".$group->update_time;
		}else{
			$since = "since=5+years+ago";
		}
		
		$until = date("Y-m-d"). " " .date("H:i:s");
		$until = DateTime::createFromFormat('Y-m-d H:i:s',$until);
		$until->sub(new DateInterval('P3D'));
		$o = new ReflectionObject($until);
		$p = $o->getProperty('date');
		$until = $p->getValue($until);
		
		
		
		$extract_group_feed = array();		
			
		try{	
	     	$extract_group_feed = (new FacebookRequest(
						$session, 'GET', '/'.$group->cid.'/feed?limit=10000&'.$since.'&until='.$until
				))->execute()->getGraphObject(GraphUser::className());
			
		} catch(Exception $e){

			try{	
		     	$extract_group_feed = (new FacebookRequest(
							$session, 'GET', '/'.$group->cid.'/feed?limit=1000&'.$since.'&until='.$until
					))->execute()->getGraphObject(GraphUser::className());
			
			}catch(Exception $e){

					try{	
				     	$extract_group_feed = (new FacebookRequest(
									$session, 'GET', '/'.$group->cid.'/feed?limit=500&'.$since.'&until='.$until
							))->execute()->getGraphObject(GraphUser::className());
					
					}catch(Exception $e){

							echo " eccezione ";

						}						

				}		

		}		
		$result = '';	
     	
			while( $extract_group_feed->getProperty('data')!= null){
							
				$j = 0;
				$extract_group_feed_data = ' ';
				$post_to_save = array('data' => array());
				$limit_cicle = 0;
						
				$extract_group_feed_data = $extract_group_feed->getProperty('data')->getProperty(0);

			while($extract_group_feed_data != null){
								
// 				$result = $this->verifyText($extract_group_feed_data, $group->degree_id);	
				
				if($result == ''){
					$this->saveGroupFeed($extract_group_feed, $group->cid, 'Degree', $session, $limit_cicle);
				}else{
					
					$course = Courses::model()->findByAttributes(array('name' => $result, 'degree_id' => $group->degree_id ));
					$exam_group = '';
					
					if($course != null)
						$exam_group = ExamGroup::model()->findByAttributes(array('courses_id' => $course->cid));
					
					if($course != null){
						if($exam_group == null){
							$exam_group = new ExamGroup();
							
							$criteria=new CDbCriteria;
							$criteria->select='MIN(eid) AS min';
							$row = $exam_group->model()->find($criteria);
							$min_id = $row['min'];
							
							$exam_group->eid = $min_id - 1;					
						}
																
							$exam_group->degree_id = $group->degree_id;
							$exam_group->degree_group_id = $group->cid;
							$exam_group->courses_id = $course->cid;
							$exam_group->name = $course->name;
							
							$exam_group->validate();
							
							$exam_group->save();
							
							$this->saveGroupFeed($extract_group_feed, $exam_group->eid, 'Exam', $session, $limit_cicle);
					}else{
						$this->saveGroupFeed($extract_group_feed, $group->cid, 'Degree', $session, $limit_cicle);
					}
					
										
					
				}
				
					$j++;
					$limit_cicle = $j;
					$extract_group_feed_data = $extract_group_feed->getProperty('data')->getProperty($j);
			}
			
			
			$next = '';
			
			if($extract_group_feed->getProperty('paging') != null){
				$next = $extract_group_feed->getProperty('paging')->getProperty('next');
				
				$next = str_replace('https://graph.facebook.com/v2.0/', '', $next);
				$next = str_replace('https://graph.facebook.com/v2.1/', '', $next);
		
				$extract_group_feed = (new FacebookRequest(
					$session, 'GET', '/'.$next
				))->execute()->getGraphObject(GraphUser::className());
			
			
			}else{
				
				$extract_group_feed = null; 
			}
								
// 			$extract_group_feed = (new FacebookRequest(
// 					$session, 'GET', '/'.$group->cid.'/feed?limit='.$limit.'&offset='.$offset
// 			))->execute()->getGraphObject(GraphUser::className());
			
			
		}
		

		$group = DegreeGroup::model()->findByPk($cid);

		$datetime = DateTime::createFromFormat(DateTime::ISO8601,$extract_group_info->getProperty('updated_time'));

		$o = new ReflectionObject($datetime);
		$p = $o->getProperty('date');
		$date = $p->getValue($datetime);
				
		$group->update_time = $date;

		$group->validate();
		$group->save();

		
	/*
		$limit = 25;
		$offset = 0;
				
			$extract_group_docs = (new FacebookRequest(
					$session, 'GET', '/'.$group->cid.'/docs?limit=15000&since=5+years+ago&until=now'
			))->execute()->getGraphObject(GraphUser::className());
		
			while($extract_group_docs->getProperty('data') != null){			
				$this->saveGroupDocs($extract_group_docs, $group->cid,'Degree');
				
				$next = '';
				
				if($extract_group_docs->getProperty('paging') != null && $extract_group_docs->getProperty('paging')->getProperty('next') != null){
					$next = $extract_group_docs->getProperty('paging')->getProperty('next');
				
					$next = str_replace('https://graph.facebook.com/v2.0/', '', $next);
					$next = str_replace('https://graph.facebook.com/v2.1/', '', $next);
				
					$extract_group_docs = (new FacebookRequest(
							$session, 'GET', '/'.$next
					))->execute()->getGraphObject(GraphUser::className());
				
				
				}else{
				
					$extract_group_docs = null;
				}
				
				
// 				$offset = $offset + $limit;
				
// 				$extract_group_docs = (new FacebookRequest(
// 						$session, 'GET', '/'.$group->cid.'/docs?limit='.$limit.'&offset='.$offset
// 				))->execute()->getGraphObject(GraphUser::className());
			}
				
			*/
		/*
			$limit = 25;
			$offset = 0;	
				
			
			$extract_group_files = (new FacebookRequest(
					$session, 'GET', '/'.$group->cid.'/files?limit=15000&since=5+years+ago&until=now'
			))->execute()->getGraphObject(GraphUser::className());

						
			while($extract_group_files != null && $extract_group_files->getProperty('data') != null){
						
				$this->saveGroupFiles($extract_group_files, $group->cid, 'Degree');
				
				$next = '';
				
				if($extract_group_files->getProperty('paging') != null && $extract_group_files->getProperty('paging')->getProperty('next') != null ){
					$next = $extract_group_files->getProperty('paging')->getProperty('next');
				
					$next = str_replace('https://graph.facebook.com/v2.0/', '', $next);
					$next = str_replace('https://graph.facebook.com/v2.1/', '', $next);
					
					$extract_group_files = (new FacebookRequest(
							$session, 'GET', '/'.$next
					))->execute()->getGraphObject(GraphUser::className());
				
				
				}else{
			
					$extract_group_files = null;
				}
				
				
// 				$offset = $offset + $limit;
				
// 				$extract_group_files = (new FacebookRequest(
// 						$session, 'GET', '/'.$group->cid.'/files?limit='.$limit.'&offset='.$offset
// 				))->execute()->getGraphObject(GraphUser::className());
				
			}	*/	
			
		}
		
		$this->performAjaxValidation2($group);
		//$this->render('results', array('group'=> $group));
	}
	
	
	public function verifyText($post, $group_id){
		
		$course_name = '';
		
		if($post->getProperty('message') != null){
			$message = $post->getProperty('message');
			
			$i = 1;
			$tag = '';
		
			if($message[0] == '['){
				while($i < strlen($message) && $message[$i] != ']'){
					$tag = $tag.$message[$i];
					$i++;
				}
				
			}
			if($tag != ''){
				$course_name = $this->detectAcronym($tag, $group_id);
				
				if(strlen($course_name) == 0){					
					$course_name = $this->detectTag($tag, $group_id);
				}
			}
		}
			return $course_name;
	}
	
	public function saveGroupMember($data, $group_id, $param){
		
		
		$member = ' ';
		$i = 0;
		
		$member = $data->getProperty($i);
		while($member != null){
						
			$this->saveMemberInfo($member);
			
			$this->saveNewMember($member, $group_id, $param);
			$i++;
			$member = $data->getProperty($i);
			
		}
		
	}
	
	public function saveNewMember($user, $group_id, $param){
		$param1 = strtolower($param);
		
		$result = Yii::app()->db->createCommand()
			->select('user_id, user_group_id')
			->from('member_'.$param1.'_group')
			->where('user_id=:id and user_group_id=:group_id', array(':id'=>$user->getProperty('id'), ':group_id' => $group_id))
			->queryRow();
	
		if($result == null){
			
			$class = new ReflectionClass('Member'.$param.'Group');
			$member = $class->newInstanceArgs();
			
			$member->user_id = $user->getProperty('id');
			$member->user_group_id = $group_id;
			$member->actual_member = 1;
			
			$member->validate();
			$member->save();
		
			
		}
		
		
		
		
	}
	
	public function saveGroupDocs($data, $group_id, $param){
		
		$i = 0 ;
		
		$group_doc_data = $data->getProperty('data');
		
		while($group_doc_data->getProperty($i) != null){
			
			$group_doc_element = $group_doc_data->getProperty($i); 			
			
			$group_doc = FbDoc::model()->findByPk($group_doc_element->getProperty('id'));
			
			if($group_doc == null)
				$group_doc = new FbDoc();
			
			$group_doc->fbdid = $group_doc_element->getProperty('id');
			$group_doc->message = $group_doc_element->getProperty('message');
			$group_doc->subject = $group_doc_element->getProperty('subject');
			
			$created_datetime = DateTime::createFromFormat(DateTime::ISO8601,$group_doc_element->getProperty('created_time'));
			$created_datetime->add(new DateInterval('PT1H'));	
			
			$o = new ReflectionObject($created_datetime);
			$p = $o->getProperty('date');
			$created_date = $p->getValue($created_datetime);
						
			$group_doc->created_time = $created_date; 
			
			$updated_datetime = DateTime::createFromFormat(DateTime::ISO8601,$group_doc_element->getProperty('updated_time'));
			$updated_datetime->add(new DateInterval('PT1H'));
			$o = new ReflectionObject($updated_datetime);
			$p = $o->getProperty('date');
			$updated_date = $p->getValue($updated_datetime);
			
			$group_doc->updated_time = $updated_date;
			
			
			if($param == 'Exam')
				$group_doc->exam_group_id = $group_id;
			else
				$group_doc->degree_group_id = $group_id;
			
			
			$this->saveMemberInfo($group_doc_element->getProperty('from'));
			$this->saveMember($group_doc_element->getProperty('from'), $group_id, $param);
			
			$group_doc->author_id = $group_doc_element->getProperty('from')->getProperty('id');
			$group_doc->validate();
			$group_doc->save();
			
			
				
			if($group_doc_data->getProperty('comments') != null)
				$this->saveComments($group_doc_element->getProperty('comments'), $group_doc->fbdid, 'Docs', $group_id, 'Exam', $session);
			
			
			$i++;
		
		}
		
		
		
		
	}
	
	public function saveGroupFiles($data, $group_id, $param){
		
		$i = 0;
		
		
		$files_element = $data->getProperty('data');
		
		while($files_element->getProperty($i) != null){
			
			$single_file_element =   $files_element->getProperty($i);
			
			$file = FbFiles::model()->findByPk($single_file_element->getProperty('id'));
			
			if($file == null)
				$file = new FbFiles();			
			
			$file->fbfid = $single_file_element->getProperty('id');
			$file->download_link = $single_file_element->getProperty('download_link');
			
			
// 			$opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));
// 			$context = stream_context_create($opts);
// 			$getted_file  = file_get_contents($single_file_element->getProperty('download_link'),false,$context);
			
			$getted_file = null;
			
			if($this->get_http_response_code($single_file_element->getProperty('download_link')) != "404")
				$getted_file = file_get_contents($single_file_element->getProperty('download_link'));
			
			
			
// 			$getted_file = file_get_contents($single_file_element->getProperty('download_link'));
			
			
			$j = 0;
			for($j = strlen($file->download_link)-1 ; $j>= 0 ; $j--){
				
				if($file->download_link[$j] == '/'){
					break;
				}
				
			}
			$name = '';
			for($k= $j ; $k < strlen($file->download_link); $k++  ){
				
				$name = $name.$file->download_link[$k]; 

			}
			$name = str_replace('/', '\\', $name);
			$name = str_replace('%20', ' ',$name);
			
			
			$final_path = Yii::getPathOfAlias('webroot').'\files_'.$param.$name;
			$final_path = str_replace('/', '\\', $final_path);

			if($getted_file != null)
				file_put_contents($final_path, $getted_file);
			else{
				$file->download_link = $file->download_link.' [ROTTO]';
			}
			$file->local_path =  $final_path;  
				
			
			$updated_datetime = DateTime::createFromFormat(DateTime::ISO8601,$single_file_element->getProperty('updated_time'));
			$updated_datetime->add(new DateInterval('PT1H'));	
			
			$o = new ReflectionObject($updated_datetime);
			$p = $o->getProperty('date');
			$updated_date = $p->getValue($updated_datetime);
			$file->updated_time = $updated_date;
			
			
			if($param == 'Exam')
				$file->exam_group_id = $group_id;
			else 
				$file->degree_group_id = $group_id;
			 
			$this->saveMemberInfo($single_file_element->getProperty('from'));
			$this->saveMember($single_file_element->getProperty('from'), $group_id, $param);
			
			$file->author_id = $single_file_element->getProperty('from')->getProperty('id');
			
			
			if($single_file_element->getProperty('comments') != null)
				$this->saveComments($single_file_element->getProperty('comments'), $file->fbfid, 'Docs', $group_id, 'Exam', $session);
			
			
			$file->validate();

			$file->save();
			$i++;
		}
		
	}
	
	
	function get_http_response_code($url) {
		$headers = get_headers($url);
		return substr($headers[0], 9, 3);
	}


	public function readNames(){
		
		$member = UserPersonalInfo::model()->findAll();
		
		foreach ($member as $m){
			$GLOBALS['Names'][strtolower(trim($m->first_name))] = ' ';
			$GLOBALS['Names'][strtolower(trim($m->last_name))] = ' ';
		}
		
	}
	
	
	public function removeNames($message){

		$message_tmp = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $message);
		$message_array = explode(" ", $message_tmp);			

		foreach($message_array as $ma){
			
			$ma = trim($ma);
			
			if(strlen($ma) > 2  && array_key_exists(strtolower($ma), $GLOBALS['Names'])){
				$message = str_ireplace($ma, '', $message);
			
			}
		}	
		
		/*$member = UserPersonalInfo::model()->findAll();
						
		foreach($member as $m){
			
			if(strlen($m->first_name) > 2 )
				$message = preg_replace('/\b'.$m->first_name.'\b/i', '', $message);
			if(strlen($m->last_name) > 2 )
				$message = preg_replace('/\b'.$m->last_name.'\b/i', '', $message);
			
// 			$message = str_ireplace($m->first_name, '', $message);
// 			$message = str_ireplace($m->last_name, '', $message);
		}*/
		
		return $message;
		
	}
	
	public function readBadWords(){
		
			
		$handle = fopen(Yii::app()->basePath."/modules/filtered_word/filtered_word.txt", "r");
		
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
	
				$line = trim($line);
				$GLOBALS['badWords'][strtolower($line)] = ' ';
// 				array_push($GLOBALS['badWords'], $line);
					
			}
	
			fclose($handle);
		} else {
			// error opening the file.
		}
	}
	
	public function removeBadWords($message){
  

  		$message_tmp = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $message);
		$message_array = explode(" ",$message_tmp);
		
		foreach ($message_array as $ma){
			
			$ma = trim($ma);
			if(array_key_exists( strtolower($ma), $GLOBALS['badWords'] ))
				$message = str_ireplace($ma, '', $message);
				
		}
				
	/*	foreach ($GLOBALS['badWords'] as $bw){
		        
		    	$message = str_ireplace($bw, '', $message);
		
		}			 	
	*/
		return $message;
	}
	
	public function saveGroupFeed($data, $group_id, $param, $session, $limit){
		
		if($limit == -1){
			$i = 0;
			$limit = 100000;
		}else 
			$i = $limit;
		
		$data_extracted_vector = ' ';
		
	
		$data_extracted = $data->getProperty('data');
	
			
		while($data_extracted->getProperty($i) != null && $i<= $limit){
				
				$post_id = $data_extracted->getProperty($i)->getProperty('id');
				
				$caption = strpos($post_id, '_');
				
				$post_id = substr($post_id, $caption+1);
				$post = $data_extracted->getProperty($i);

				$group_feed = FbPost::model()->findByPk($post_id);
			
			if($group_feed == null){
				$group_feed = new FbPost();
			}		
				
			
			$group_feed->fbpid = $post_id;
	
			$group_feed->caption = $caption;
			$group_feed->author_id = $post->getProperty('from')->getProperty('id');
			
	 		$this->saveMemberInfo($post->getProperty('from'));
	 		$this->saveMember($post->getProperty('from'), $group_id, $param);
	 		
				 		
			$group_feed->message = $this->removeNames($post->getProperty('message'));
			
			$group_feed->message = $this->removeBadWords($group_feed->message);
			

			$group_feed->link = $post->getProperty('link');
			
			$group_feed->type = $post->getProperty('type');
			
			if($post->getProperty('application') != null)
				$group_feed->application = $post->getProperty('application')->getProperty('name');
			
			
			$created_datetime = DateTime::createFromFormat(DateTime::ISO8601,$post->getProperty('created_time'));
			$created_datetime->add(new DateInterval('PT1H'));
			$o = new ReflectionObject($created_datetime);
			$p = $o->getProperty('date');
			
			$created_date = $p->getValue($created_datetime);
			
			$group_feed->created_time = $created_date;
					
			$updated_datetime = DateTime::createFromFormat(DateTime::ISO8601,$post->getProperty('updated_time'));
			$updated_datetime->add(new DateInterval('PT1H'));
			
			
			$o = new ReflectionObject($updated_datetime);
			$p = $o->getProperty('date');
			$update_date = $p->getValue($updated_datetime);
			
			$group_feed->updated_time = $update_date;
			
			if($param == 'Exam')
				$group_feed->exam_group_id = $group_id;
	 		else if($param == 'Degree')
	 			$group_feed->degree_group_id = $group_id;
	 		
	 		
	 		$group_feed->validate();
	 		
	 		
	 		if($group_feed->save()){
			
				if($post->getProperty('likes') != null){
		  			$this->saveLikes($post->getProperty('likes')->getProperty('data'), $post_id, 'Post');
				}
				
		 		if($post->getProperty('object_id') != null && $post->getProperty('properties') == null){
					try{
						$this->savePhotos($post->getProperty('object_id'), $post_id,'Post', $session);
					}catch(Exception $e){
						echo 'eccezione';
					}
	 	
		 		}
		 		
		 		if($post->getProperty('comments') != null){
		 			$this->saveComments($post->getProperty('comments'), $post_id, 'Post', $group_id, $param, $session);
		 		}
	 		}	
			$i++;
		}
		
		
		
// 		var_dump($data_extracted);
		
		
		
	}
	
	
	public function saveComments($comments, $ref_entity_id, $param_class, $group_id, $param_group, $session){
		
		$i = 0;
		
		$comments = $comments->getProperty('data');
		
		while($comments->getProperty($i) != null){
						
			$single_comment = $comments->getProperty($i);
			
			if($param_class == 'Post'){
				$fb_comment = FbPostComment::model()->findByPk($single_comment->getProperty('id'));
				
			}else if($param_class == 'Doc'){
				$fb_comment = FbDocComment::model()->findByPk($single_comment->getProperty('id'));
				
			}else if($param_class == 'Files'){
				$fb_comment = FbFilesComment::model()->findByPk($single_comment->getProperty('id'));
				
			}
			
			if($fb_comment == null){
				$class = new ReflectionClass('Fb'.$param_class.'Comment');
				$fb_comment = $class->newInstanceArgs();
			}
			
			$fb_comment->cid 		= $single_comment->getProperty('id');
			
			$fb_comment->author_id  = $single_comment->getProperty('from')->getProperty('id');
			
			$fb_comment->like_count = $single_comment->getProperty('like_count');
			$fb_comment->message 	= $this->removeNames($single_comment->getProperty('message'));
			$fb_comment->message	= $this->removeBadWords($fb_comment->message);
			
			$created_datetime = DateTime::createFromFormat(DateTime::ISO8601,$single_comment->getProperty('created_time'));
			$created_datetime->add(new DateInterval('PT1H'));
			$o = new ReflectionObject($created_datetime);
			$p = $o->getProperty('date');
				
			$created_date = $p->getValue($created_datetime);
				
			$fb_comment->created_time = $created_date;
					
			
			$fb_comment->ref_entity_id = $ref_entity_id;
			
			$this->saveMemberInfo($single_comment->getProperty('from'));
			
			$fb_comment->validate();
			$fb_comment->save();
			
			$this->saveMember($single_comment->getProperty('from'), $group_id, $param_group);

			if($single_comment->getProperty('obejct_id') != null && $post->getProperty('properties') == null){
				$this->savePhotos($single_comment->getProperty('obejct_id'),$fb_comment->cid, 'Comment', $session);
			}
			
			$i++;
		}
		
		
		
		
		
	}
	
	public function savePhotos($photo_id, $element_id, $param,$session){
		
		$i = 0;
		$param1 = strtolower($param);
		$result = Yii::app()->db->createCommand()
			->select('fid')
			->from('photo_fb_'.$param1)
			->where('fid=:id', array(':id'=> $photo_id))
			->queryRow();
		
		if($result == null){
		
			$extract_photo = (new FacebookRequest(
					$session, 'GET', '/'.$photo_id
			))->execute()->getGraphObject(GraphUser::className());
						
			$class = new ReflectionClass('PhotoFb'.$param);
			$photo = $class->newInstanceArgs();
			
			
	
			$photo->fid = $photo_id;
			$photo->element_id = $element_id;		
					
			
			$img = 	file_get_contents($extract_photo->getProperty('source'));

			$param2 = strtolower($param);

	
	 		$final_path = Yii::getPathOfAlias('webroot').'\photos_'.$param2;
	 		$final_path = str_replace('/', '\\', $final_path);


			$final_path = $final_path.'\\'.$photo_id.".jpeg";

			$final_path = str_replace('\\', '/', $final_path);
			
			echo " ". file_put_contents($final_path, $img) ;
			
			
			$photo->medium = $final_path ;

			//$photo->author_id = $extract_photo->getProperty('from')->getProperty('id');

			$photo->author_id = null;

			$photo->validate();

			$photo->save();
		}
		
	}
	
	public function saveLikes($likes, $post_id, $param){
		
		$i = 0;
		
				
		while($likes->getProperty($i) != null){
			
			$this->saveMemberInfo($likes->getProperty($i));
			
			$param1 = strtolower($param);
			
			$result = Yii::app()->db->createCommand()
				->select('user_id, ref_entity_id')
				->from('like_fb_'.$param1)				
				->where('user_id=:id and ref_entity_id=:ref_id', array(':id'=>$likes->getProperty($i)->getProperty('id'), ':ref_id' => $post_id))
				->queryRow();
			
							
				$class = new ReflectionClass('LikeFb'.$param);
				$like_post = $class->newInstanceArgs();
			
			
			if($result == null){
									
				$like_post->user_id = $likes->getProperty($i)->getProperty('id');
							
				$like_post->ref_entity_id = $post_id; 
				
				$like_post->validate();
							
				$like_post->save();

			}
			$i++;
		}
		
	}
	
	public function saveDegreeGroupInfo($data, $degree_id){
		
		$group = DegreeGroup::model()->findByPk($data->getProperty('id'));
		
		if($group == null)
			$group = new DegreeGroup();
		
		$degree = new Degree();
		
		$degree = Degree::model()->findByPk($degree_id);
		
		$group->cid = $data->getProperty('id');
		
		$group->name = $data->getProperty('name');
		
		//$datetime->add(new DateInterval('PT1H'));	
					
		$group->email = $data->getProperty('email');
		
		if(null != $data->getProperty('owner')){
			$group->owner_id = $data->getProperty('owner')->getProperty('id');
			$this->saveMemberInfo($data->getProperty('owner'));
			$this->saveGroupMember($data->getProperty('owner'), $data->getProperty('id'), 'Degree');
		}
		
		$group->description = $data->getProperty('description');
			
		$group->degree_id = $degree_id;
		 		
				
		$group->save();
			
			
		
	}
	
	public function saveExamGroupInfo($data, $course_id){
		
		$group = ExamGroup::model()->findByPk($data->getProperty('id'));				
		
		if($group == null)
			$group = new ExamGroup();
		
		$course = new Courses();
		
		$course = Courses::model()->findByPk($course_id);		
		
		$group->eid = $data->getProperty('id');

		$group->name = $data->getProperty('name');
		
		
			//$datetime->add(new DateInterval('PT1H'));
			
					
			$group->email = $data->getProperty('email');
		
			if(null != $data->getProperty('owner')){
				$group->owner_id = $data->getProperty('owner')->getProperty('id');
				$this->saveMemberInfo($data->getProperty('owner'));
				$this->saveGroupMember($data->getProperty('owner'), $data->getProperty('id'), 'Exam');
			}
		
			$group->description = $data->getProperty('description');
			
			$group->courses_id = $course_id;
			
			if(isset($course)){
				$group->degree_id = $course->degree_id;
			}
			
			$degreeGroup = DegreeGroup::model()->findByAttributes(array('degree_id' => $course->degree_id));
			
			if($degreeGroup != null){
				$group->degree_group_id = $degreeGroup->cid;
			}
			
			$group->save();			
			
			
		
	}
	
	public function saveMember($user_info, $group_id , $param){
	
	
		if($param == 'Degree'){
			$member = MemberDegreeGroup::model()->findByPk(array('user_id' => $user_info->getProperty('id'), 'user_group_id' => $group_id));
			
		}else if($param == 'Exam'){
			$id = $user_info->getProperty('id');
			$member = MemberExamGroup::model()->findByPk(array('user_id' => $id, 'user_group_id' => $group_id));
			
		}
		 
		if($member == null){
			$class = new ReflectionClass('Member'.$param.'Group');
			$member = $class->newInstanceArgs();
		}
		
		$member->user_id = $user_info->getProperty('id');
		$member->user_group_id = $group_id;
		
		$member->validate();
		$member->save();
		
	}
	
	
	public function saveMemberInfo($data){
		
		
		$user_info = new Users();
		
		$user_info = Users::model()->findByPk($data->getProperty('id'));
		
	
		if($user_info == null){
			$user_info = new Users();
			$user_info->id = $data->getProperty('id');
						
			$user_info->email = $data->getProperty('id').'@qea.it';
			$user_info->nickname = $data->getProperty('id');
				
			$user_info->validate();
			
			$user_info->save();
		}
		
		$user_info->usersPersonalInfo = UserPersonalInfo::model()->findByPk($data->getProperty('id'));
		
		if($user_info->usersPersonalInfo == null)
			$user_info->usersPersonalInfo = new UserPersonalInfo();
		
			$user_info->usersPersonalInfo->user_id = $data->getProperty('id');
			$names = str_word_count($data->getProperty('name'),1);
			
			if(count($names ) >2 ){
				
				$user_info->usersPersonalInfo->first_name = $names[0].' '.$names[1];
				$user_info->usersPersonalInfo->last_name = $names[2];
			}else{
				if(isset($names[0])){
					$user_info->usersPersonalInfo->first_name = $names[0];
				}
				if(isset($names[1]))
					$user_info->usersPersonalInfo->last_name = $names[1];
			}			
			
			$user_info->usersPersonalInfo->validate();
			
			$user_info->usersPersonalInfo->save();
		
	}
	
	protected function detectTag($word1, $cdl_id){
		
		$exams_list = Courses::model()->findAllByAttributes(array('degree_id'=> $cdl_id));
	
		$max_percent = 0;
		$selected_course = "";
		
		$course_info = array('name' =>'',
								'percent' =>0);
					
		foreach ($exams_list as $el){
			
			$word1 = strtolower($word1);
			$el->name = strtolower($el->name);
			
			similar_text($word1, $el->name, $percent);
			
			if($percent > 60){
							
				if($percent > $max_percent){
					$max_percent = $percent;
					$selected_course = $el->name;
				}
			}
		}
		
		$course_info['name'] = $selected_course;
		$course_info['percent'] = $max_percent;
		
		
		
		return $course_info;
		
	}
	
	protected function detectAcronym($acronym, $cdl_id){
		
// 		$possible_exams = array();
		$possible_exams = '';
		
		$stop_words = array('dello', 'della', 'negli', 'degli', 'dell\'', 'all\'', 'agli', 'alle', 'del');
		
 		$exams_list = Courses::model()->findAllByAttributes(array('degree_id'=> $cdl_id));
// 		$exams_list = Courses::model()->findAllByAttributes(array('name'=> 'Metodi avanzati di programmazione'));
		foreach ($exams_list as $key => $el){
			
			$current_word = $el->name;
			$acronym = strtolower($acronym);
			$current_word = strtolower($current_word);
			
			foreach ($stop_words as $sw){
				
				$current_word = str_replace($sw, '', $current_word);				
			}
			
					
			$words = str_word_count($current_word,1);
			
			for($j = 0; $j < count($words); $j++){
				
				if(strlen($words[$j]) <= 3 ){
					
					array_splice($words,$j,1);
					
				}
			}
	
		 
			if(strlen($acronym) == count($words)){
				$index = 1;
				for($i = 0 ; $i < count($words); $i++){
				
					if($acronym[$i] != $words[$i][0]){

						
						$index = 0;
						break;
					}
					
				}
				if($index == 1){
// 					array_push($possible_exams,$el->name);
				$possible_exams = $el->name;	
				}
				
			}

		}
		return $possible_exams;
		
	}
	
	
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='fb-form-exam') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	protected function performAjaxValidation2($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='fb-form-degree') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}
?>
<?php
Yii::import('application.modules.users.*');
Yii::import('application.modules.users.models.UserPersonalInfo');
Yii::import('application.modules.users.models.Users');
Yii::import('application.modules.degree.models.Degree');
Yii::import('application.modules.courses.models.Courses');
// Yii::import('application.modules.users.models.MemberDegreeGroup');
// Yii::import('application.modules.users.models.MemberExamGroup');


require 'vendor/autoload.php';

	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\GraphUser;
	use Facebook\GraphObject;


	
class StatController extends Controller
{
	
	public function ActionIndex(){
		
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		
		
		$myfile = fopen('C:\Users\fabio\Documents\ONEYEAR - BoxPostAnswerTime.csv', 'w') or die("Unable to open file!");
		$GLOBALS['myfile'] = $myfile;
		
		fwrite($GLOBALS['myfile'], "NAME;SECONDS;TYPE;CITY\n");
		
//  		fwrite($GLOBALS['myfile'], "SECOND_ELAPSED_AFTER_QUESTIONS_ARE_POSTED;PERCENTAGE_OF_ANSWERED_QUESTIONS\n");
		
//  		fwrite($GLOBALS['myfile'], "GRUPPO;POST_DAY_RATIO;TYPE;SEMESTER;CITY\n");
		
		$degree_group = DegreeGroup::model()->findAll(array('order' => 'mapping ASC'));
		
		$exam_group = ExamGroup::model()->findAll(array('order' => 'mapping ASC'));
				
//  	$this->setUserActivityPerSemester();
		
// 		$this->countUserActivityPerSemester();
		
// 		$this->showPostWithNComments();

// 		$this->countThanks();
		
// 		$this->medianAnswerTime();
		
// 		$this->countUserPostPerType();		
		
// 		$this->setUserType();
		
// 		$this->userActivityInTime();
		
// 		$this->countUserActivity();
		
// 		$this->setUserActivity();
		
// 		$this->meanAnswerTimePerMonth('','','');
		
// 		$this->setIntervalTime();
		
// 		$this->percentageIntervalTime('TOTAL', "", "","");
		
// 		$this->showMonthPosts('', '', '');
		
// 		$this->showTrimesterPosts('','','');
		
		$i = 0;
		foreach ($degree_group as $d){
			$id = $d->cid;
			$mapping = $d->mapping;

// 				$this->boxPlotPostComment($id, $mapping, 'degree', $d->city);

// 				$this->boxPlotPostLike($id, $mapping, 'degree', $d->city);

// 				$this->boxPlotCommentLike($id, $mapping, 'degree', $d->city);

				$this->boxPlotAnswerTime($id, $mapping, 'degree', $d->city);
			
// 				$this->medianAnswerTime($id, $mapping, 'degree');
//  			$this->calcolatePostDayRatioSemesterDivision($id, $mapping, 'degree');
			
// // 			$this->meanAnswerTimePerMonth($id, $mapping, 'degree');
			
// // 			$this->showTrimesterPosts($id, $mapping, "degree");
			
// // 			$this->showMonthPosts($id, $mapping, "degree");
			
// 			$this->percentageIntervalTime($id, $mapping, "degree",'');
			
// // 			$this->showThreadLenght($id, $mapping, 'degree');
// 			$this->showNoAnsweredPosts($id, $mapping, 'degree');
//  			$this->groupInfo($id);
// // 			$this->calcolateMeanAnswerTime($id);
//  				$this->calcolatePostDayRatio($id, $d->name);
// // 			$i++;
			
		}
		
		foreach ($exam_group as $e){
			$id = $e->eid;
			$mapping = $e->mapping;
			if($id != 10000){
				
// 					$this->boxPlotPostComment($id, $mapping, 'exam', $e->city);
				
// 					$this->boxPlotPostLike($id, $mapping, 'exam', $e->city);

// 					$this->boxPlotCommentLike($id, $mapping, 'exam', $e->city);					

					$this->boxPlotAnswerTime($id, $mapping, 'exam', $e->city);
				
// 					$this->medianAnswerTime($id, $mapping, 'exam');
					
//  				$this->calcolatePostDayRatioSemesterDivision($id, $mapping, 'exam');
				
// // 				$this->meanAnswerTimePerMonth($id, $mapping, 'exam');
				
// // 				$this->showTrimesterPosts($id, $mapping, "exam");
				
// // 				$this->showMonthPosts($id, $mapping, "exam");
				
// 				$this->percentageIntervalTime($id, $mapping, "exam", $myfile, '');
				
// // 				$this->showThreadLenght($id, $mapping, 'exam');
// 				$this->showNoAnsweredPosts($id, $mapping, 'exam');
// 				$this->groupInfo($id);
// // 				$this->calcolateMeanAnswerTime($id);
// 					$this->calcolatePostDayRatio($id, $e->name);
			}
		}
		
		
	}
	
	
	public function boxPlotPostComment($id, $mapping, $type, $city){
		
		
	 
		$criteria=New CDbCriteria;
		$criteria->condition=$type.'_group_id='.$id.' and created_time >= "2013-09-01 00:00:00" and created_time <= "2014-08-31 00:00:00"';
		
		$posts = FbPost::model()->findAllByAttributes(array($type."_group_id" => $id), $criteria);
		
		foreach ($posts as $p){
			
			$txt = $mapping.";".$p->n_answer.";".$type."_group".";".$city."\n";
			
			fwrite($GLOBALS['myfile'],$txt);
		}
	}
	
	
	public function boxPlotPostLike($id, $mapping, $type, $city){
	
	
	
		$criteria=New CDbCriteria;
		$criteria->condition=$type.'_group_id='.$id.' and created_time >= "2013-09-01 00:00:00" and created_time <= "2014-08-31 00:00:00"';
	
		$posts = FbPost::model()->findAllByAttributes(array($type."_group_id" => $id), $criteria);
		

		foreach ($posts as $p){
			
			$like = LikeFbPost::model()->findAllByAttributes(array('ref_entity_id' => $p->fbpid));
			
			$txt = $mapping.";".count($like).";".$type."_group".";".$city."\n";
			fwrite($GLOBALS['myfile'],$txt);
		}
	
	}	
	
	
	
	public function boxPlotCommentLike($id, $mapping, $type, $city){
	
	
		$criteria=New CDbCriteria;
		$criteria->condition=$type.'_group_id='.$id.' and created_time >= "2013-09-01 00:00:00" and created_time <= "2014-08-31 00:00:00"';
		
			
		$posts = FbPost::model()->findAllByAttributes(array($type."_group_id" => $id),$criteria);
		
		foreach ($posts as $p ){
			
			 

			$criteria2=New CDbCriteria;
			$criteria2->condition= 'ref_entity_id='.$p->fbpid .' and created_time >= "2013-09-01 00:00:00" and created_time <= "2014-08-31 00:00:00"';
			
			$comment = FbPostComment::model()->findAllByAttributes(array('ref_entity_id' => $p->fbpid), $criteria2);
			
			if(isset($comment)){
				foreach ($comment as $c ){
					$txt = $mapping.";".$c->like_count.";".$type."_group".";".$city."\n";
					fwrite($GLOBALS['myfile'],$txt);
				}
			}
		}
	}
		
	

	
	public function boxPlotAnswerTime($id, $mapping, $type, $city){
		
	
		$criteria=New CDbCriteria;
		$criteria->condition=$type.'_group_id='.$id.' and created_time >= "2013-09-01 00:00:00" and created_time <= "2014-08-31 00:00:00" and first_answer_time > 0';
		
		$criteria2=New CDbCriteria;
		$criteria2->condition="first_answer_time > 0";
		
		$posts = FbPost::model()->findAllByAttributes(array($type."_group_id" => $id),$criteria);
		
		foreach ($posts as $p){
			
			$txt = $mapping.";".$p->first_answer_time.";".$type."_group".";".$city."\n";
				
			fwrite($GLOBALS['myfile'],$txt);
			
		}
		
	}
	
	
	public function showPostWithNComments(){
		
		$connection = Yii::app()->db;
		$command = $connection->createCommand("SELECT ref_entity_id, count(*) as c FROM qeanalysis.fb_post_comment group by ref_entity_id having c = 9 limit 30");
		$posts = $command->queryAll();
		
		foreach ($posts as $p){
			
			$post = FbPost::model()->findByPk($p['ref_entity_id']);
			
			$comments = FbPostComment::model()->model()->findAllByAttributes(array('ref_entity_id' => $post->fbpid ), array('order' => 'created_time ASC'));
				
			
			?>
			
			<div class="panel panel-info">
			  <div class="panel-heading"><?php echo 'MESSAGGIO : ' .$post->fbpid.' '. $post->message, "\n"; ?></div>
			  <div class="panel-body">
			    
			 
			<?php 
			
			foreach ($comments as $c){
				
				echo ' COMMENTI : '.$c->message; ?> <br>
			<?php 
			}
			
			?>
			
			 </div>
			</div>
			 <br><br><br>
			<?php 
		}
		
	}
	
	public function countThanks(){
		
		$comment = FbPostComment::model()->findAll();
		
		$grazie_counter = 0;
		$thx_counter = 0;
		$grz_counter = 0;
		$thanks_counter = 0;
		
		foreach($comment as $c){
			
			$c->message = strtolower($c->message);
			
			if(strpos($c->message, 'grazie') != false ){
				
				$grazie_counter += 1;
				
			}else if(strpos($c->message, 'grz')!= false ){
				
				$grz_counter += 1;
				
			}else if(strpos($c->message, 'thx')!= false){
				
				$thx_counter += 1;
				
			}else if(strpos($c->message, 'thanks')!= false){
				
				$thanks_counter += 1;
				
			}
					
		}
		
		echo ' n grazie '. $grazie_counter . ' n. grz '. $grz_counter . ' n thx '. $thx_counter. ' n thanks '.$thanks_counter;
	}
	
	public function medianAnswerTime($id, $name, $type){
		
		
		if(!isset($id)){
			$post = FbPost::model()->findAll();
			
			$first_time = array();
			
			foreach ($post as $p ){
				
				if($p->first_answer_time != 0 )
					array_push($first_time, $p->first_answer_time);
			}
			
			$median = $this->calculate_median($first_time);
			
			echo $median ;
			
		}else{
			
			$criteria=New CDbCriteria;
			$criteria->condition= $type."_group_id ='".$id."' and created_time >= '2013-09-01 00:00:00' and created_time <= '2014-08-31 00:00:00'";
			
			
			$post = FbPost::model()->findAll($criteria);
				
			$first_time = array();
				
			foreach ($post as $p ){
			
				if($p->first_answer_time != 0 )
					array_push($first_time, $p->first_answer_time);
			}
				
			$median = $this->calculate_median($first_time);
				
			$txt = $name.';'.$median.';'.$type.'_group;'."\n";
			
			fwrite($GLOBALS['myfile'], $txt);
			
			echo $median .' ';
			
		}
		
	}
	
	public function userActivityInTime(){
				

		$user = Users::model()->findAll();
		
		foreach ($user as $u){
			$u->activity = '';
					
			$ending_post = new DateTime('2014-12-01 00:00:00');
			
			$end_data = new DateTime('2010-9-01 00:00:00');
			
			$start_data = new DateTime('2010-09-01 00:00:00');
			
					
			while($end_data < $ending_post){
									
				$end_data->add(new DateInterval('P6M'));
							
				$start_data_string = $start_data->format('Y-m-d H:i:s');
				$end_data_string = $end_data->format('Y-m-d H:i:s');
			
				$trim_post = Yii::app()->db->createCommand()
					->select('*')
					->from('fb_post')
					->where('created_time >:sdata and created_time <=:edata and author_id=:id', array(':sdata'=>$start_data_string, ':edata' => $end_data_string,':id' => $u->id))
					->order('created_time ASC')
					->queryAll();
					
	
				$trim_comment = Yii::app()->db->createCommand()
					->select('*')
					->from('fb_post_comment')
					->where('created_time >:sdata and created_time <=:edata and author_id=:id', array(':sdata'=>$start_data_string, ':edata' => $end_data_string, ':id' => $u->id))
					->order('created_time ASC')
					->queryAll();
				

			$activity = count($trim_comment) + count($trim_post);

				if($activity == 0){
					$u->activity = $u->activity. 'N';
					echo $u->activity;
					?><br><br><?php
										
				}else if($activity > 0 && $activity <= 4){
					
					$u->activity = $u->activity.'L';
					echo $u->activity;
					?><br><br><?php
										
				}else if($activity > 4 && $activity <= 16){
					
					$u->activity = $u->activity.'M';
					echo $u->activity;
					?><br><br><?php
					
				}else if($activity > 16){
					$u->activity = $u->activity.'H';
					echo $u->activity;
					?><br><br><?php
				}
			
				$u->validate();
				$u->save();

				$start_data->add(new DateInterval('P6M'));
			}
		}
		echo ' OK ';
		
		
	}
	
	public function countUserPostPerType(){
		
				$top_post = 0;
				$top_comment = 0;
				$top_like = 0;
				$top_doc = 0;
				$top_files = 0;
				
				$low_post = 0;
				$low_comment = 0;
				$low_like = 0;
				$low_doc = 0;
				$low_files = 0;
				
				$medium_post = 0;
				$medium_comment = 0;
				$medium_like = 0;
				$medium_doc = 0;
				$medium_files = 0;
				
				$ss_post = 0;
				$ss_comment = 0;
				$ss_like = 0;
				$ss_doc = 0;
				$ss_files = 0;
							
				
				$top = Users::model()->findAllByAttributes(array('user_type' => 'top'));
				
				$medium = Users::model()->findAllByAttributes(array('user_type' => 'medium'));
				
				$low = Users::model()->findAllByAttributes(array('user_type' => 'low'));
				
				$ss = Users::model()->findAllByAttributes(array('user_type' => 'shooting star'));
				
				foreach ($top as $t){
					
					$tpost = FbPost::model()->findAllByAttributes(array('author_id' => $t->id));
					$tcomment = FbPostComment::model()->findAllByAttributes(array('author_id'=> $t->id));
					$tlike = LikeFbPost::model()->findAllByAttributes(array('user_id' => $t->id));
					$tdoc = FbDoc::model()->model()->findAllByAttributes(array('author_id' => $t->id));
					$tfiles = FbFiles::model()->model()->findAllByAttributes(array('author_id' => $t->id));
					
					$top_comment += count($tcomment);
					$top_post += count($tpost);
					$top_like += count($tlike);
					$top_doc += count($tdoc);
					$top_files += count($tfiles);
			
				}
			
				foreach ($medium as $m){
					
					$mpost = FbPost::model()->findAllByAttributes(array('author_id' => $m->id));
					$mcomment = FbPostComment::model()->findAllByAttributes(array('author_id'=> $m->id));
					$mlike = LikeFbPost::model()->findAllByAttributes(array('user_id' => $m->id));
					$mdoc = FbDoc::model()->model()->findAllByAttributes(array('author_id' => $m->id));
					$mfiles = FbFiles::model()->model()->findAllByAttributes(array('author_id' => $m->id));
					
					$medium_comment += count($mcomment);
					$medium_post += count($mpost);
					$medium_like += count($mlike);
					$medium_doc += count($mdoc);
					$medium_files += count($mfiles);
				}
				
				foreach ($low as $l){
					
					$lpost = FbPost::model()->findAllByAttributes(array('author_id' => $l->id));
					$lcomment = FbPostComment::model()->findAllByAttributes(array('author_id'=> $l->id));
					$llike = LikeFbPost::model()->findAllByAttributes(array('user_id' => $l->id));
					$ldoc = FbDoc::model()->model()->findAllByAttributes(array('author_id' => $l->id));
					$lfiles = FbFiles::model()->model()->findAllByAttributes(array('author_id' => $l->id));
					
					$low_comment += count($lcomment);
					$low_post += count($lpost);
					$low_like += count($llike);
					$low_doc += count($ldoc);
					$low_files += count($lfiles);
				}
				
				foreach ($ss as $s){
					$spost = FbPost::model()->findAllByAttributes(array('author_id' => $s->id));
					$scomment = FbPostComment::model()->findAllByAttributes(array('author_id'=> $s->id));
					$slike = LikeFbPost::model()->findAllByAttributes(array('user_id' => $s->id));
					$sdoc = FbDoc::model()->model()->findAllByAttributes(array('author_id' => $s->id));
					$sfiles = FbFiles::model()->model()->findAllByAttributes(array('author_id' => $s->id));
						
					$ss_comment += count($scomment);
 					$ss_post += count($spost);
 					$ss_like += count($slike);
 					$ss_doc += count($sdoc);
 					$ss_files += count($sfiles);
				}
				
				$total_top = $top_comment + $top_post;
				$total_low = $low_comment + $low_post;
				$total_medium = $medium_comment + $medium_post;
				$total_ss = $ss_comment + $ss_post;
				
				$top_ratio =   $total_top/count($top);
				$low_ratio =  $total_low/count($low);
				$medium_ratio =   $total_medium / count($medium);
				$ss_ratio =  $total_ss / count($top);
				
				echo "i top hanno postato ".$top_post." volte e commentato ".$top_comment." per un totale di ".$total_top. ' ratio '. $top_ratio. " hanno messo like ".$top_like. " caricato files ".$top_files. " creato docs ".$top_doc ?><br><br><?php 
				echo "i medium hanno postato ".$medium_post." volte e commentato ".$medium_comment." per un totale di ". $total_medium. ' ratio '. $medium_ratio. " hanno messo like ".$medium_like. " caricato files ".$medium_files. " creato docs ".$medium_doc  ?><br><br><?php
				echo "i low hanno postato ".$low_post." volte e commentato ".$low_comment." per un totale di ".$total_low. ' ratio '. $low_ratio. " hanno messo like ".$low_like. " caricato files ".$low_files. " creato docs ".$low_doc  ?><br><br><?php
				echo "i ss hanno postato ".$ss_post." volte e commentato ".$ss_comment." per un totale di ".$total_ss. ' ratio '. $ss_ratio. " hanno messo like ".$ss_like. " caricato files ".$ss_files. " creato docs ".$ss_doc ?><br><br><?php
	}
	
	public function setUserType(){
		
		$users = Users::model()->findAll();
		
		foreach ($users as $u){
			
			if(substr_count($u->activity, "H") > 3 ){
				$u->user_type = "top";				

			}else if(substr_count($u->activity, "H") == 0 && substr_count($u->activity, "M") < 2 && (substr_count($u->activity, "L") > 0 || substr_count($u->activity, "M") > 0)){
				$u->user_type = "low";
			}else if(substr_count($u->activity, "H") > 0 && substr_count($u->activity, "H") < 2){
				$u->user_type = "shooting star";
			}else if(substr_count($u->activity, "N") != 9){
				$u->user_type = "medium";
			}else if(substr_count($u->activity, "N") == 9){
				$u->user_type = "lurkers";
			}
				
			
			$u->validate();
			$u->save();
		}
	}
	
	public function countUserActivity(){
		
			$myfile = fopen('C:\Users\fabio\Documents\UA-CP.csv', 'w') or die("Unable to open file!");
			$j = 0;
			fwrite($myfile, "NUMBER_OF_POST_AND_COMMENT;NUMBER_OF_USERS;GROUP\n");
			$i = 0;
			
					
			while($i<=990){
				
// 				$j += 10;
// 				$connection = Yii::app()->db;
// 				$command = $connection->createCommand("SELECT count(*) FROM users where (n_post + n_comment ) >".$i." and (n_post + n_comment ) <=".$j);
// 				$total_posts = $command->queryAll();
						
// 				$user_percent = ($total_posts[0]['count(*)'] *100) / 2869;
				
				$j += 10;
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT count(*) FROM users where n_post >".$i." and n_post <=".$j);
				$total_posts = $command->queryAll();
				
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT count(*) FROM users where n_comment >".$i." and n_comment <=".$j);
				$total_comments = $command->queryAll();
				
				
							
				$txt = $j.'; '.$total_posts[0]['count(*)'].';'. "post\n";
				
				fwrite($myfile, $txt);
				
				echo $txt;
				
				$txt = $j.'; '.$total_comments[0]['count(*)'].';'. "comment\n";
				
				echo $txt;
				
				?><br><br><?php
				
				fwrite($myfile, $txt);
				
				$i +=10;
			}
			fclose($myfile);
	}
	
	public function countUserActivityPerSemester(){
			
		$k= 1;

		$myfile = fopen('C:\Users\fabio\Documents\UA'.$k.'.csv', 'w') or die("Unable to open file!");
		$j = 0;
		fwrite($myfile, "NUMBER_OF_POST_AND_COMMENT;NUMBER_OF_USERS;\n");
		
		while($k <= 7){

				$myfile = fopen('C:\Users\fabio\Documents\UA'.$k.'.csv', 'w') or die("Unable to open file!");
				$j = 0;
				fwrite($myfile, "NUMBER_OF_POST_AND_COMMENT;NUMBER_OF_USERS\n");
				
			$i = 0;
		
			while($i<=500){
			
				// 				$j += 10;
				// 				$connection = Yii::app()->db;
				// 				$command = $connection->createCommand("SELECT count(*) FROM users where (n_post + n_comment ) >".$i." and (n_post + n_comment ) <=".$j);
				// 				$total_posts = $command->queryAll();
			
				// 				$user_percent = ($total_posts[0]['count(*)'] *100) / 2869;
			
				$j += 5;
				
				if($k == 1){

					$connection = Yii::app()->db;
					$command = $connection->createCommand("SELECT count(*) FROM users where first_semester >".$i." and first_semester <=".$j);
					$total_posts = $command->queryAll();
						
// 					$connection = Yii::app()->db;
// 					$command = $connection->createCommand("SELECT count(*) FROM users where first_semester >".$i." and first_semester <=".$j);
// 					$total_comments = $command->queryAll();
					
				}else if($k == 2){

					$connection = Yii::app()->db;
					$command = $connection->createCommand("SELECT count(*) FROM users where second_semester >".$i." and second_semester <=".$j);
					$total_posts = $command->queryAll();
					
// 					$connection = Yii::app()->db;
// 					$command = $connection->createCommand("SELECT count(*) FROM users where second_semester >".$i." and second_semester <=".$j);
// 					$total_comments = $command->queryAll();
					
				}else if($k == 3){
					
					$connection = Yii::app()->db;
					$command = $connection->createCommand("SELECT count(*) FROM users where third_semester >".$i." and third_semester <=".$j);
					$total_posts = $command->queryAll();
						
// 					$connection = Yii::app()->db;
// 					$command = $connection->createCommand("SELECT count(*) FROM users where third_semester >".$i." and third_semester <=".$j);
// 					$total_comments = $command->queryAll();

				}else if($k == 4){
					
					$connection = Yii::app()->db;
					$command = $connection->createCommand("SELECT count(*) FROM users where fourth_semester >".$i." and fourth_semester <=".$j);
					$total_posts = $command->queryAll();
					
// 					$connection = Yii::app()->db;
// 					$command = $connection->createCommand("SELECT count(*) FROM users where fourth_semester >".$i." and fourth_semester <=".$j);
// 					$total_comments = $command->queryAll();

				}else if($k == 5){

					$connection = Yii::app()->db;
					$command = $connection->createCommand("SELECT count(*) FROM users where fifth_semester >".$i." and fifth_semester <=".$j);
					$total_posts = $command->queryAll();
						
// 					$connection = Yii::app()->db;
// 					$command = $connection->createCommand("SELECT count(*) FROM users where fifth_semester >".$i." and fifth_semester <=".$j);
// 					$total_comments = $command->queryAll();
					
				}else if($k == 6){

					$connection = Yii::app()->db;
					$command = $connection->createCommand("SELECT count(*) FROM users where sixth_semester >".$i." and sixth_semester <=".$j);
					$total_posts = $command->queryAll();
					
// 					$connection = Yii::app()->db;
// 					$command = $connection->createCommand("SELECT count(*) FROM users where sixth_semester >".$i." and sixth_semester <=".$j);
// 					$total_comments = $command->queryAll();
					
				}else if($k == 7){
					
					$connection = Yii::app()->db;
					$command = $connection->createCommand("SELECT count(*) FROM users where seventh_semester >".$i." and seventh_semester <=".$j);
					$total_posts = $command->queryAll();
						
// 					$connection = Yii::app()->db;
// 					$command = $connection->createCommand("SELECT count(*) FROM users where seventh_semester >".$i." and seventh_semester <=".$j);
// 					$total_comments = $command->queryAll();
				}
				
	 			$sum =  $total_posts[0]['count(*)'];
					
				$txt = $j.'; '.$sum."\n";
			
				fwrite($myfile, $txt);
			
				echo $txt;
			
						
				?><br><br><?php
							
				
						
				$i +=5;
						}
				fclose($myfile);
				
				$k++;
			}
				
		}
	
	
	
	public function setUserActivity(){
		
			$user = Users::model()->findAll();
			
			foreach ($user as $u){
				
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT count(*) FROM fb_post where author_id =".$u->id);
				$total_posts = $command->queryAll();
				
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT count(*) FROM fb_post_comment where author_id =".$u->id);
				$total_comment = $command->queryAll();
				
				
				$u->n_comment = $total_comment[0]['count(*)'];
				$u->n_post = $total_posts[0]['count(*)'];

				$u->validate();				
				$u->save();
			}
			echo 'OK';
	}
	
	public function setUserActivityPerSemester(){
	
// 		$user = Users::model()->findAll();
		
		$s_semester = new DateTime('2011-09-01 00:00:00');
		
		$e_semester = new DateTime('2011-09-01 00:00:00');
		
		$end_data = new DateTime('2014-09-01 00:00:00');
				
		
		$e_semester->add(new DateInterval('P6M'));
		
		$k = 1;
		
		while($e_semester <= $end_data){
				
// 			$user = Users::model()->findAll();
					
// 			$start_semester = $s_semester->format('Y-m-d H:i:s');
// 			$end_semester = $e_semester->format('Y-m-d H:i:s');
			
// 			foreach ($user as $u){
		
// 				$connection = Yii::app()->db;
// 				$command = $connection->createCommand("SELECT count(*) FROM fb_post where author_id =".$u->id." and created_time > '".$start_semester. "' and created_time <='".$end_semester."'");
// 				$total_posts = $command->queryAll();
		
// 				$connection = Yii::app()->db;
// 				$command = $connection->createCommand("SELECT count(*) FROM fb_post_comment where author_id =".$u->id." and created_time > '".$start_semester. "' and created_time <='".$end_semester."'");
// 				$total_comment = $command->queryAll();
		
// 				if($k == 1){
	
// 					$u->first_semester = $total_comment[0]['count(*)'] + $total_posts[0]['count(*)'];
					
					
// 				}else if($k ==2){
					
// 					$u->second_semester = $total_comment[0]['count(*)'] + $total_posts[0]['count(*)'];
					
// 				}else if($k ==3){
					
// 					$u->third_semester = $total_comment[0]['count(*)'] + $total_posts[0]['count(*)'];
// 				}else if($k ==4){
					
// 					$u->fourth_semester = $total_comment[0]['count(*)'] + $total_posts[0]['count(*)'];
// 				}else if($k ==5){
// 					$u->fifth_semester = $total_comment[0]['count(*)'] + $total_posts[0]['count(*)'];
// 				}else if($k ==6){
// 					$u->sixth_semester = $total_comment[0]['count(*)'] + $total_posts[0]['count(*)'];
// 				}else if($k ==7){
// 					$u->seventh_semester = $total_comment[0]['count(*)'] + $total_posts[0]['count(*)'];
// 				}
				
				
// 				$u->validate();
// 				$u->save();
// 			}
		echo 'OK '. $k. ' sem '.
		var_dump($s_semester); 
		echo ' end '. 
		var_dump($e_semester);
		?><br><br><?php 
		$s_semester->add(new DateInterval('P6M'));
		$e_semester->add(new DateInterval('P6M'));
		$k++;
		
		
		}
		
		
	}
	
	
	public function percentageIntervalTime($id,$gruppo, $type_group, $myfile ){
		
		$total_posts = 1;
		$txt = ' ';
		
		$time_restriction = "created_time >= '2013-09-01 00:00:00' and created_time <= '2014-08-31 00:00:00'";
		
		if($id == "TOTAL"){
// 			$myfile = fopen('C:\Users\fabio\Documents\PAT.csv', 'w') or die("Unable to open file!");
			
// 			fwrite($myfile, "SECOND_ELAPSED_AFTER_QUESTIONS_ARE_POSTED;PERCENTAGE_OF_ANSWERED_QUESTIONS\n");
			
			$connection = Yii::app()->db;
			$command = $connection->createCommand("SELECT count(*) FROM fb_post where ".$time_restriction);
			$total_posts = $command->queryAll();
			
		}else{
			
// 			$myfile = fopen('C:\Users\fabio\Documents\PAT'.$gruppo.'csv', 'w') or die("Unable to open file!");
				
// 			fwrite($myfile, "SECOND_ELAPSED_AFTER_QUESTIONS_ARE_POSTED;PERCENTAGE_OF_ANSWERED_QUESTIONS;GROUP\n");
			
			$connection = Yii::app()->db;
			$command = $connection->createCommand("SELECT count(*) FROM fb_post where ".$type_group."_group_id =".$id);
			$total_posts = $command->queryAll();
		}
		
		if($id != 'TOTAL')
			$txt =  '0;0;'.$gruppo."\n";
		else
			$txt = '0;0'."\n";
		
		fwrite($GLOBALS['myfile'], $txt);
		
		$interval_time = 60;
		
		while($interval_time < 7200){
			if($id == 'TOTAL'){
				
				
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT count(*) FROM fb_post where first_answer_time = 0 and ".$time_restriction);
				$no_answered = $command->queryAll();
								
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT count(*) FROM fb_post where first_answer_time > 0 and first_answer_time <= ".$interval_time. " and ".$time_restriction);
				$row = $command->queryAll();				
								
			}else{
				
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT count(*) FROM fb_post where ".$type_group."_group_id =".$id." and first_answer_time = 0 ");
				$no_answered = $command->queryAll();
				
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT count(*) FROM fb_post where ".$type_group."_group_id =".$id." and first_answer_time > 0 and first_answer_time <= ".$interval_time);
				$row = $command->queryAll();
									
			}
			
						
							
			$percentage = $row[0]['count(*)'] * 100 / ($total_posts[0]['count(*)'] - $no_answered[0]['count(*)']); 
			
			echo $interval_time. ';'.$percentage;	?><br><br><?php
			
			if($id != 'TOTAL')
				$txt = $interval_time. ';'.$percentage.';'.$gruppo."\n";
			else
				$txt = $interval_time. ';'.$percentage."\n";
				
				
			fwrite($GLOBALS['myfile'], $txt);
			
			$interval_time += 60;
		}
			
			?><br><br><?php
// 			fclose($myfile);
		
		
	}
	
	
	
	
	public function meanAnswerTimePerMonth($id, $gruppo, $type_group){
		
		$sum_answer_time = array();
		$median = 0;
		$mean_answer_time = 0;
	
		if($id != ''){
			$myfile = fopen('C:\Users\fabio\Documents\MAT'.$gruppo.'.csv', 'w') or die("Unable to open file!");
		}else{
			$myfile = fopen('C:\Users\fabio\Documents\MATTOTAL.csv', 'w') or die("Unable to open file!");
		}
	
	
		fwrite($myfile, "MONTH;MEDIAN_ANSWER_TIME\n");
	
		for($i= 21; $i<=69; $i++){
	
			if($id != ''){
	
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT first_answer_time FROM fb_post where ".$type_group."_group_id=".$id." and month=".$i. " and first_answer_time !=0");
				$row = $command->queryAll();
								
			}else{
					
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT first_answer_time FROM fb_post where month=".$i." and first_answer_time !=0");
				$row = $command->queryAll();
			
			}
			
			$month = Months::model()->findbyPk($i);

			foreach ($row as $r){
				array_push($sum_answer_time, $r['first_answer_time']);				 
			}
			
			if(count($sum_answer_time) != 0){
				$median = $this->calculate_median($sum_answer_time);
				
				$median = log($median, 10);
			}else{
				
				$median = 0;			
			}
			
// 			$mean_answer_time = $sum_answer_time /count($row); 			
			
			echo  ' '.$month->month.'-'.$month->year.'  '.$median; ?><br><?php
				
											
					$txt = ' '.$month->month.'-'.$month->year.';'.$median. "\n";
					fwrite($myfile, $txt);			
					
					
				$sum_answer_time = array();
				$median = 0;
				
				}
				?><br><br><?php
				fclose($myfile);
			}
	
	
	public function showTrimesterPosts($id, $gruppo, $type_group){
	
		if($id != ''){
			$myfile = fopen('C:\Users\fabio\Documents\STP'.$gruppo.'.csv', 'w') or die("Unable to open file!");
		}else{
			$myfile = fopen('C:\Users\fabio\Documents\STPTOTAL.csv', 'w') or die("Unable to open file!");
		}
	
	
		fwrite($myfile, "TRIMESTER;POST_NUMBER\n");
	
		for($i= 1; $i<=17; $i++){
	
			if($id != ''){
	
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT count(*) FROM fb_post where ".$type_group."_group_id=".$id." and trimester=".$i);
				$row = $command->queryAll();
					
			}else{
					
				$connection = Yii::app()->db;
				$command = $connection->createCommand("SELECT count(*) FROM fb_post where trimester=".$i);
				$row = $command->queryAll();
	
			}
	
			$trimester = Trimestri::model()->findbyPk($i);
				
			echo  ' ('.$trimester->start_month.'-'.$trimester->end_month.')'.'-'.$trimester->year.'  '.$row[0]['count(*)']; ?><br><?php
				
											
					$txt =' ('.$trimester->start_month.'-'.$trimester->end_month.')'.'-'.$trimester->year.';'.$row[0]['count(*)']. "\n";
					fwrite($myfile, $txt);			
					
					
				}
				?><br><br><?php
				fclose($myfile);
			}
	
	
	public function showMonthPosts($id, $gruppo, $type_group){
	
		if($id != ''){
			$myfile = fopen('C:\Users\fabio\Documents\SMP'.$gruppo.'.csv', 'w') or die("Unable to open file!");
		}else{
			$myfile = fopen('C:\Users\fabio\Documents\SMPTOTAL.csv', 'w') or die("Unable to open file!");
		}
		
		
		fwrite($myfile, "MONTH;POST_NUMBER\n");
	
		for($i= 21; $i<=69; $i++){
				
		if($id != ''){

			$connection = Yii::app()->db;
			$command = $connection->createCommand("SELECT count(*) FROM fb_post where ".$type_group."_group_id=".$id." and month=".$i);
			$row = $command->queryAll();
			
		}else{
			
			$connection = Yii::app()->db;
			$command = $connection->createCommand("SELECT count(*) FROM fb_post where month=".$i);
			$row = $command->queryAll();			

		}
		
			$month = Months::model()->findbyPk($i);
			
			echo  ' '.$month->month.'-'.$month->year.'  '.$row[0]['count(*)']; ?><br><?php
			
										
				$txt = ' '.$month->month.'-'.$month->year.';'.$row[0]['count(*)']. "\n";
				fwrite($myfile, $txt);			
				
				
			}
			?><br><br><?php
			fclose($myfile);
		}
	
	
	public function showThreadLenght($id, $gruppo, $type_group){
		
				
		$myfile = fopen('C:\Users\fabio\Documents\TL'.$gruppo.'.csv', 'w') or die("Unable to open file!");
		
		fwrite($myfile, "ANSWER_NUMBER;N_POSTS\n");
		
		for($i= 0; $i<=26; $i++){
			
			$connection = Yii::app()->db;
			$command = $connection->createCommand("SELECT count(*) FROM fb_post where ".$type_group."_group_id=".$id." and n_answer=".$i);
			$row = $command->queryAll();
			

			echo  ' '.$i.'  '.$row[0]['count(*)']; ?><br><?php
		
									
			$txt = ' '.$i.';'.$row[0]['count(*)']. "\n";
			fwrite($myfile, $txt);			
			
			
		}
		?><br><br><?php
// 		fclose($myfile);
	}
	
	
	public function showNoAnsweredPosts($id, $gruppo, $type_group){
	
	


			$gruppo  = DegreeGroup::model()->findByPk($id);
			if($gruppo == null){
				$gruppo = ExamGroup::model()->findByPk($id);
				
			}
		
			$connection = Yii::app()->db;
			$command = $connection->createCommand("SELECT count(*) FROM fb_post where ".$type_group."_group_id=".$id." and n_answer=0 and created_time >= '2013-09-01 00:00:00' and created_time <= '2014-08-31 00:00:00'");
			$row = $command->queryAll();
				
	
			echo  ' '.$gruppo->name.'  '.$row[0]['count(*)']; ?><br><?php
			
								

			?><br><br><?php

		}
	
	
	public function setAnswerNumber(){
		
		$post = FbPost::model()->findAll();
		
		foreach($post as $p){
			
				$fb_comment = FbPostComment::model()->findAllByAttributes(array('ref_entity_id' => $p->fbpid ));
				
				
				$p->n_answer = count($fb_comment);
				
				$p->validate();
				$p->save();
				
		}
		
		echo "OK";
	}
	
	public function groupInfo($id){

		$comment_number = 0;
		$like_number = 0;
		$photo_number = 0;
		$type_group = '';
		
		$group = ExamGroup::model()->findByPk($id);
		
		$criteria=New CDbCriteria;
		$criteria->condition='created_time >= "2013-09-01 00:00:00" and created_time <= "2014-08-31 00:00:00"';
		
		$criteria2=New CDbCriteria;
		$criteria2->condition='updated_time >= "2013-09-01 00:00:00" and updated_time <= "2014-08-31 00:00:00"';
		
		if($group == null){
			$group = DegreeGroup::model()->findByPk($id);
			$type_group = 'degree';
			
			
			
			$post = FbPost::model()->findAllByAttributes(array('degree_group_id' => $id),$criteria);
				
			$doc = FbDoc::model()->findAllByAttributes(array('degree_group_id' => $id),$criteria);

			$criteria2=New CDbCriteria;
			$criteria2->condition='updated_time >= "2013-09-01 00:00:00" and updated_time <= "2014-08-31 00:00:00"';
			
			$files  = FbFiles::model()->findAllByAttributes(array('degree_group_id' => $id),$criteria2);
			
			$member = MemberDegreeGroup::model()->findAllByAttributes(array('user_group_id' => $id));	
			
			
		}else{
			$type_group = 'exam';
			
			$post = FbPost::model()->findAllByAttributes(array('exam_group_id' => $id),$criteria);
			
			$doc = FbDoc::model()->findAllByAttributes(array('exam_group_id' => $id),$criteria);
			
			$files  = FbFiles::model()->findAllByAttributes(array('exam_group_id' => $id),$criteria2);
			
			$member = MemberExamGroup::model()->findAllByAttributes(array('user_group_id' => $id));
		}
		
		$comment_like = 0;
		
		
		
		
				
		foreach($post as $p){
		
			$comment = FbPostComment::model()->findAllByAttributes(array('ref_entity_id' => $p->fbpid),$criteria);
			$comment_number += count($comment);
			
			if($comment != null){
				foreach($comment as $c){
					$comment_like += $c->like_count;
				}
			}
			
			$photo = PhotoFbPost::model()->findAllByAttributes(array('element_id' => $p->fbpid));
			$photo_number += count($photo);
			
			$like = LikeFbPost::model()->findAllByAttributes(array('ref_entity_id' => $p->fbpid));
			$like_number += count($like);
		}
		
		?>	
		
		<div class=".col-md-8" style="background-color: rgb(184, 181, 181)">
		<table class="table table-bordered">		
		<thead>
		<tr>		
		<th >Nome</th>
		<th>N. membri</th>		
		<th>N. post</th>
		<th>N. Like ai post</th>
		<th>N. Commenti</th>
		<th>N. like commenti</th>
		<th>N. files</th>
		<th>N. docs</th>
		<th>N.photos post</th>
		</tr>
		</thead>
		<tbody>
		<tr>
		<td><?php echo $group->name ?></td>
		<td><?php echo count($member)?></td>		
		<td><?php echo count($post)?></td>
		<td><?php echo $like_number?></td>
		<td><?php echo $comment_number?></td>
			<td><?php echo $comment_like?></td>
				<td><?php echo count($files)?></td>
					<td><?php echo count($doc)?></td>
						<td><?php echo $photo_number?></td>
		</tr>		
		</tbody>
		</table>
		</div>
		<br>

		<?php
		
		
	}
	
	public function deleteDegreeInfo(){

		die();
		$post = FbPost::model()->findAllByAttributes(array('exam_group_id' => '123811091057378'));
				
		foreach($post as $p){
			
			$comment = FbPostComment::model()->deleteAllByAttributes(array('ref_entity_id' => $p->fbpid));
			
			$photo = PhotoFbPost::model()->deleteAllByAttributes(array('element_id' => $p->fbpid));
			
			$like = LikeFbPost::model()->deleteAllByAttributes(array('ref_entity_id' => $p->fbpid));
						
		}
		
		$doc = FbDoc::model()->deleteAllByAttributes(array('exam_group_id' => '123811091057378'));
		$files  = FbFiles::model()->deleteAllByAttributes(array('exam_group_id' => '123811091057378'));
		$post = FbPost::model()->deleteAllByAttributes(array('exam_group_id' => '123811091057378'));
		
		$exam_group = ExamGroup::model()->deleteAllByAttributes(array('eid' => '123811091057378'));
		
		$connection = Yii::app()->db;
		$command = $connection->createCommand("SELECT * FROM member_exam_group where user_group_id = '123811091057378' and user_id not in (select user_id from member_exam_group where user_group_id != '123811091057378')and user_id not in (select user_id from qeanalysis.member_degree_group where user_group_id != '123811091057378')");
		$row = $command->queryAll();
		
		foreach ($row as $r){
			
			
				$user_personal_info = UserPersonalInfo::model()->deleteByPk($r['user_id']);
				echo $r['user_id'];
				$user = User::model()->deleteByPk($r['user_id']);
				
				$member = MemberExamGroup::model()->deleteAllByAttributes(array('user_id' => $r['user_id'], 'user_group_id' => '123811091057378'));
			
		}
		
// 		echo ' post: '. $post . ' user '. $user. ' personal info: '. $user_personal_info. '  '.$member ;
	}
	
	public function setIntervalTime(){
		
		$post =	FbPost::model()->find(array('order' => 'created_time ASC'));
		
		$n_month = 1;
		$n_trimester = 1;
		
		$ending_post = new DateTime('2014-11-26 16:59:47');
		
		$end_data = new DateTime('2010-10-01 00:00:00');
		
		$start_data = new DateTime('2010-09-01 00:00:00');
		
// 		$start_data->sub(new DateInterval('PT1H'));
		
// 		$end_data = new DateTime($post->created_time);
		
		while($end_data < $ending_post){
			
			if($n_month % 3 == 0){
				
				$n_trimester += 1;
			}
			
			
			$end_data->add(new DateInterval('P1M'));

			
			$start_data_string = $start_data->format('Y-m-d H:i:s');
			$end_data_string = $end_data->format('Y-m-d H:i:s');
						
			$month_post = Yii::app()->db->createCommand()
			->select('*')
			->from('fb_post')
			->where('created_time >:sdata and created_time <=:edata', array(':sdata'=>$start_data_string, ':edata' => $end_data_string))
			->order('created_time ASC')
			->queryAll();
			
			
			foreach($month_post as $mp){
			
				$post = FbPost::model()->findByPk($mp['fbpid']);				

				$post->month = $n_month + 19;
				$post->trimester = $n_trimester;
				$post->validate();
				$post->save();
			}
				
			$start_data->add(new DateInterval('P1M'));
			
			$n_month += 1;

			
		}
		echo ' OK ';
		
	}
	
	public function calcolatePostDayRatio($id, $name){
		
// 		$post =	FbPost::model()->find(array('degree_group_id'=>$id, 'order' => 'created_time ASC'));
		
		$post = Yii::app()->db->createCommand()
					->select('*')
					->from('fb_post')
					->where('(degree_group_id =:id OR exam_group_id =:id) and created_time >= "2013-09-01 00:00:00" and created_time <= "2014-08-31 00:00:00"', array(':id'=> $id))
					->order('created_time ASC')
					->queryRow();

		$n_day = 1;
		$post_count = 0 ;
		
		$ending_post = new DateTime('2014-08-31 00:00:00');
		
		$end_data = new DateTime($post['created_time']);
		
		$start_data = new DateTime($post['created_time']);
		
		$end_data = new DateTime($post['created_time']);
		
		while($end_data < $ending_post){

					
			$end_data->add(new DateInterval('PT24H'));

			$start_data_string = $start_data->format('Y-m-d H:i:s');
			$end_data_string = $end_data->format('Y-m-d H:i:s');			
			
			$day_post = Yii::app()->db->createCommand()
				->select('*')
				->from('fb_post')
				->where('created_time >=:sdata and created_time <=:edata and (exam_group_id =:id OR degree_group_id =:id)', array(':sdata'=>$start_data_string, ':edata' => $end_data_string, ':id'=> $id))				
				->queryAll();

			
			$start_data->add(new DateInterval('PT24H'));
			
			$post_count += count($day_post);
			$n_day++;
			
						 		
			
		}
		
		$post_day_mean = $post_count / $n_day;
		
		echo $name . '<b>La media dei post al giorno e: </b>'.$post_day_mean;
		?><br><br><?php
	}
	
	
	public function calcolatePostDayRatioSemesterDivision($id, $group, $type){
	

// 		$myfile = fopen('C:\Users\fabio\Documents\PostDayRatio.csv', 'w') or die("Unable to open file!");
		

		// 		$post =	FbPost::model()->find(array('degree_group_id'=>$id, 'order' => 'created_time ASC'));
		$post = Yii::app()->db->createCommand()
			->select('*')
			->from('fb_post')
			->where('degree_group_id =:id OR exam_group_id =:id', array(':id'=> $id))
			->order('created_time ASC')
			->queryRow();
		
		$i = 0;
		$j = 0;
		$r = 0;
		
		$first_n_day = 0;
		$first_post_count = 0 ;
		
		$second_n_day = 0;
		$second_post_count = 0 ;
	
		$ending_post = new DateTime('2014-08-31 00:00:00');
		
		$start_data = new DateTime('2013-09-01 00:00:00');
		
		$first_semester = new DateTime('2013-09-01 00:00:00');
		$first_semester->add(new DateInterval('P6M'));
		
		$second_semester = new DateTime('2014-03-02 00:00:00');		
		$second_semester->add(new DateInterval('P6M'));
		
	
		$end_data = new DateTime('2013-09-01 00:00:00');
	
// 		while($end_data < $ending_post){
		
			$r++;
		
			while($start_data < $ending_post){
			
				$i++;
				$end_data->add(new DateInterval('P6M'));
		
				$start_data_string = $start_data->format('Y-m-d H:i:s');
				$end_data_string = $end_data->format('Y-m-d H:i:s');
					
				$day_post = Yii::app()->db->createCommand()
					->select('*')
					->from('fb_post')
					->where('created_time >=:sdata and created_time <=:edata and (exam_group_id =:id OR degree_group_id =:id)', array(':sdata'=>$start_data_string, ':edata' => $end_data_string, ':id'=> $id))
					->queryAll();
		
					
				$start_data->add(new DateInterval('P1Y'));
				$end_data->add(new DateInterval('P6M'));
				if($day_post != null)
					$first_post_count += count($day_post);
				
								
				
				if($first_post_count == 0){				
					$first_n_day = 0;
					}else{
						$first_n_day += 181;
					}
					
				
				
			}
			$start_data = new DateTime('2014-03-01 00:00:00');
			$end_data = new DateTime('2014-03-01 00:00:00');
			while($start_data < $ending_post){
				
				$j++;	
				$end_data->add(new DateInterval('P6M'));
				
				$start_data_string = $start_data->format('Y-m-d H:i:s');
				$end_data_string = $end_data->format('Y-m-d H:i:s');
					
				$day_post = Yii::app()->db->createCommand()
					->select('*')
					->from('fb_post')
					->where('created_time >=:sdata and created_time <=:edata and (exam_group_id =:id OR degree_group_id =:id)', array(':sdata'=>$start_data_string, ':edata' => $end_data_string, ':id'=> $id))
					->queryAll();
			
					
				$start_data->add(new DateInterval('P1Y'));
				$end_data->add(new DateInterval('P6M'));
				if($day_post != null)
					$second_post_count += count($day_post);
				
				$second_n_day += 0 ;
				

				if($second_post_count == 0){
					$second_n_day = 0;
				}else{
						$second_n_day += 184;
					}				
					
// 			}						
			
		}		
		
		
		$first_post_day_mean = round($first_post_count / $first_n_day, 1);
		$second_post_day_mean = round($second_post_count / $second_n_day,1);
	
		$txt = $group.';'.$first_post_day_mean.';'.$type.'_group;'.'first'."\n";
		$txt2 = $group.';'.$second_post_day_mean.';'.$type.'_group;'.'second'."\n";
		
		fwrite($GLOBALS['myfile'], $txt);
		fwrite($GLOBALS['myfile'], $txt2);
		
		echo $group. ' ';
		echo '<b>La media dei post al giorno nel primo semestre è: </b>'.$first_post_day_mean;
		echo '<b>La media dei post al giorno nel secondo semestre è: </b>'.$second_post_day_mean;
		
		?><br><br><?php
		}
	
	public function calcolateMeanAnswerTime($id){
		
		$sum_time_minutes = 0;
		$no_answer_question = 0;
		$array_sample = array();
// 		$allPost = FbPost::model()->findAll();
 		$allPost = FbPost::model()->findAllByAttributes(array('degree_group_id' => $id));
 		if($allPost == null){
 			$allPost = FbPost::model()->findAllByAttributes(array('exam_group_id' => $id));
 		}
		
 	
		foreach($allPost as $post){
			
			$minutes_single_post = 0;

			$post_time = new DateTime($post->created_time);
			
			$comment = FbPostComment::model()->findByAttributes(array('ref_entity_id' => $post->fbpid),array('order' => 'created_time ASC')); 

			if(isset($comment)){
				$comment_time = new DateTime($comment->created_time);			
				
				$sum_time = $comment_time->diff($post_time);
							
				
				$minutes_single_post +=  $sum_time->d * 24 * 60 * 60;
				$minutes_single_post +=	 $sum_time->h * 60 * 60;
				$minutes_single_post +=	 $sum_time->i * 60;
				$minutes_single_post +=	 $sum_time->s;
				
				$post->first_answer_time = $minutes_single_post;
				
				
				array_push($array_sample, $minutes_single_post);
				
				$sum_time_minutes += $sum_time->d * 24 * 60 * 60;
				$sum_time_minutes += $sum_time->h * 60 * 60;
				$sum_time_minutes += $sum_time->i * 60;
				$sum_time_minutes += $sum_time->s;
				
				
				
			}else{

				$post->first_answer_time = 0;
				
				$no_answer_question += 1;
			}
			
// 			$post->validate();
// 			$post->save();
		}	
		
		$consideredPost = count($allPost) - $no_answer_question;
		echo '<b>Post Considerati: </b>'. $consideredPost;
		?><br><br><?php
		echo '<b>Post senza risposta: </b>'.$no_answer_question;
		?><br><br><?php
		echo '<b>Il Tempo medio e: </b>'.$mean_answer_time = $sum_time_minutes / $consideredPost. ' secondi ';
		?><br><br><?php
		
		$dev = 0 ;
		
		foreach ($array_sample as $s){
			
			$dev += pow($s - $mean_answer_time, 2) ;
			
			
		
		}
		$dev_std = sqrt($dev / $consideredPost -1 );
		echo '<b>La deviazione standard e: </b>'. $dev_std . ' secondi ';
		?><br><br><?php
		
	}
	
	function calculate_median($arr) {
		sort($arr);
		$count = count($arr); //total numbers in array
		$middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
		if($count % 2) { // odd number, middle is the median
			$median = $arr[$middleval];
		} else { // even number, calculate avg of 2 medians
			$low = $arr[$middleval];
			$high = $arr[$middleval+1];
			$median = (($low+$high)/2);
		}
		return $median;
	}
}

?>
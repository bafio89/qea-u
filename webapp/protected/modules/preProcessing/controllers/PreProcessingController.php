<?php
                                                      
Yii::import('application.modules.users.models.UserPersonalInfo');
Yii::import('application.modules.users.models.Users');
Yii::import('application.modules.fb_extraction.models.FbPost');
Yii::import('application.modules.fb_extraction.models.PreProcPost2');
Yii::import('application.modules.fb_extraction.models.LikeFbPost');
Yii::import('application.modules.fb_extraction.models.FbPostComment');
Yii::import('application.modules.fb_extraction.models.PhotoFbPost');

$GLOBALS['badWords'] = array();
$GLOBALS['Names'] = array();

class PreProcessingController extends Controller
{
	

	public function actionIndex()
	{
		set_time_limit(0);
		echo 'cdasao';
//		$this->removeNamesAndWords();
		
		die();
// 		$this->featuresRappresentation();
		
// 		$this->datasetL2Preparation();
// 		$this->actionReadTrainingSetL2();
		
// 		$this->updateBaseline();
		
// 		$this->createBaseline();

// 		$this->actionCreateFileBaseline();

// 		$this->actionCreateFileToValidate();

// 		$this->actionReadTrainingSet();

// 		$this->actionUpdateFeatures();
	}
	
	public function removeNamesAndWords(){
		
// 		$post = FbPost::model()->findAll(array("limit" => "2000", "offset" => "25000" ));
// 		$post = FbPost::model()->findAll();
				
// 		$this->readBadWords();
// 		$this->readNames();
		
// 		foreach($post as $p){
			
// 			$p->message = $this->removeBadWords($p->message);
// 			$p->message = $this->removeNames($p->message);

			
// 			$p->save();
			
// 		}
		
		$comment = FbPostComment::model()->findAll(array("limit" => "50000", "offset" => "100000" ));
	
		$this->readBadWords();
		$this->readNames();
				
		foreach ($comment as $c ){
		
			$c->message = $this->removeBadWords($c->message);
			$c->message = $this->removeNames($c->message);
		
			$c->save();
		
		}
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
// 				$message = preg_replace('/\b'.$m->first_name.'\b/i', '', $message);
// 			if(strlen($m->last_name) > 2 )
// 				$message = preg_replace('/\b'.$m->last_name.'\b/i', '', $message);
				
			// 			$message = str_ireplace($m->first_name, '', $message);
			// 			$message = str_ireplace($m->last_name, '', $message);
		}
	
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
	
	
	public function datasetL2Preparation(){
	
	
		$path = "C:/Users/fabio/Google Drive/TESI - Q&A SL/TESI 2.0/Classificazione/gold standard 2/direzione.txt";
		$direzione = fopen($path, 'w') or die("Unable to open file!");
		
		$path = "C:/Users/fabio/Google Drive/TESI - Q&A SL/TESI 2.0/Classificazione/gold standard 2/Anna.txt";
		$anna = fopen($path, 'r') or die("Unable to open file!");
		
		$path = "C:/Users/fabio/Google Drive/TESI - Q&A SL/TESI 2.0/Classificazione/gold standard 2/Antonio.txt";
		$antonio = fopen($path, 'r') or die("Unable to open file!");
		
		$path = "C:/Users/fabio/Google Drive/TESI - Q&A SL/TESI 2.0/Classificazione/gold standard 2/Fabio.txt";
		$fabio = fopen($path, 'r') or die("Unable to open file!");
		$line = "";
		$i = 0;
		
		while(!feof($anna)){
		
		echo $i. " " ;
			$anna_line = fgets($anna);
			$antonio_line = fgets($antonio);
			$fabio_line = fgets($fabio);
			$anna_line = str_replace('"', "", $anna_line);
			$anna_line = explode(";", $anna_line);
			$antonio_line = str_replace('"', "", $antonio_line);
			$antonio_line = explode(";", $antonio_line);
			$fabio_line = str_replace('"', "", $fabio_line);
			$fabio_line = explode(";", $fabio_line);

			
			

		
			if(isset($fabio_line[2]) && isset($anna_line[2]) && isset($antonio_line[2])){
				$fabio_line[2] = trim($fabio_line[2]);
				$fabio_line[1] = trim($fabio_line[1]);
				
				$antonio_line[2] = trim($antonio_line[2]);
				$antonio_line[1] = trim($antonio_line[1]);
				
				$anna_line[2] = trim($anna_line[2]);
				$anna_line[1] = trim($anna_line[1]);
				if($anna_line[2] == "si" && $antonio_line[2] == "si" && $fabio_line[2] == "si" ){
					$i++;
					$line =  $anna_line[1]." ".$antonio_line[1]." ". $fabio_line[1]."\n";
					var_dump($line);
					
					fwrite($direzione, $line);
				}
			}
		}
		
		fclose($direzione);
		
	}
	
	
	public function actionUpdateFeatures(){
		set_time_limit(0);
		$post = FeaturesRappresentation::model()->findAll();
		
		foreach ($post as $p){
			
			
			if($p->n_char_comment_length != 0 && $p->n_comment != 0){
				$p->n_char_comment_length_ratio = round($p->n_char_comment_length/$p->n_comment, 4);
				$p->save();
				
			}
		}
		echo 'OK';
	}
	
	public function actionReadNewTrainingSet(){
		
		$path = "C:/Users/fabio/Google Drive/TESI - Q&A SL/TESI 2.0/Classificazione/Nuovo modello/Training set - l1.csv";
		$myfile = fopen($path, 'r') or die("Unable to open file!");
			
		$i = 1;
		$line = fgets($myfile);
		$count_type = 0;
			
		while(!feof($myfile)){
			
			$line = fgets($myfile);
			// 			$line = str_replace(";", " ", $line);
			$line = str_replace('"', "", $line);
			$line = explode(";", $line);
				

			$post = FbPost::model()->findByPk($line[0]);
			$preproc = PreProcPost2::model()->findByPk($line[0]);
			
			if(isset($line[2]) && ($line[2] == 'useful' || $line[2] == 'spam') ){
			
				$line[2] = str_replace(" ", "", $line[2]);
				$line[3] = str_replace(" ", "", $line[3]);
									
				$line[2] = trim($line[2]);
				$line[3] = trim($line[3]);
				
				$post->post_type_l1 = $line[2];
				$preproc->post_type_l1 = $line[2];
				
				if(isset($line[3]) && ($line[3] == 'si' || $line[3] == 'no')){
					$post->post_type_l3 = $line[3];
					$preproc->post_type_l3 = $line[3];
				}
				
				$post->save();
				$preproc->save();
				
				
			}
				
				
		}
		
	}
	
	public function actionReadTrainingSetL1(){
		
		
		$path = "C:/Users/fabio/Google Drive/TESI - Q&A SL/TESI 2.0/Classificazione/gold standard 2/gold 1.csv";
		$myfile = fopen($path, 'r') or die("Unable to open file!");
		
		
		
		
		$i = 1;
		$line = fgets($myfile);
		$count_type = 0;
					
		while(!feof($myfile)){
						

			$line = fgets($myfile);
// 			$line = str_replace(";", " ", $line);
			$line = str_replace('"', "", $line);
			$line = explode(";", $line);
							
			$post = FbPost::model()->findByPk($line[0]);
			$preproc = PreProcPost2::model()->findByPk($line[0]);
			
			if(isset($post) && isset($line[2]) && isset($line[3]) && isset($line[4]) ){

				$line[2] = str_replace(" ", "", $line[2]);
				$line[3] = str_replace(" ", "", $line[3]);
				$line[4] = str_replace(" ", "", $line[4]);
					
				$line[2] = trim($line[2]);
				$line[3] = trim($line[3]);
				$line[4] = trim($line[4]);
								
				if($line[2] == $line[3] && $line[2] == $line[4] ){
					$post->post_type_l1 = $line[2];
					$preproc->post_type_l1 = $line[2];
					
 					$post->save();
 					$preproc->save();
				}
					
				
			}
			
		}
		
		fclose($myfile);
		
	}
	
	public function actionReadTrainingSetL2(){
	
	
		$path = "C:/Users/fabio/Google Drive/TESI - Q&A SL/TESI 2.0/Classificazione/gold standard 2/gold 2.csv";
		$myfile = fopen($path, 'r') or die("Unable to open file!");
	
	
	
	
		$i = 1;
		$line = fgets($myfile);
		$count_type = 0;
			
		while(!feof($myfile)){
	
	
			$line = fgets($myfile);
			// 			$line = str_replace(";", " ", $line);
			$line = str_replace('"', "", $line);
			$line = explode(";", $line);
				
			$post = FbPost::model()->findByPk($line[0]);
			$preproc = PreProcPost2::model()->findByPk($line[0]);
				
			if(isset($line[2]) && isset($line[3]) && isset($line[4]) ){
	
				$line[2] = str_replace(" ", "", $line[2]);
				$line[3] = str_replace(" ", "", $line[3]);
				$line[4] = str_replace(" ", "", $line[4]);
					
				$line[2] = trim($line[2]);
				$line[3] = trim($line[3]);
				$line[4] = trim($line[4]);
	
				if($line[2] == $line[3] && $line[2] == $line[4]){
					
					if($post->post_type_l3 == "si"){
						$post->post_type_l2 = $line[2];
						$preproc->post_type_l2 = $line[2];
					}else{
						$post->post_type_l2 = null;
						$preproc->post_type_l2 = null;
					}
					
					$post->save();
					$preproc->save();
				}
					
	
			}
				
		}
	
		fclose($myfile);
	
	}
	
	public function actionReadTrainingSetL3(){
	
	
		$path = "C:/Users/fabio/Google Drive/TESI - Q&A SL/TESI 2.0/Classificazione/gold standard 2/gold 3.csv";
		$myfile = fopen($path, 'r') or die("Unable to open file!");
	
	
	
	
		$i = 1;
		$line = fgets($myfile);
		$count_type = 0;
			
		while(!feof($myfile)){
	
	
			$line = fgets($myfile);
			// 			$line = str_replace(";", " ", $line);
			$line = str_replace('"', "", $line);
			$line = explode(";", $line);
				
			$post = FbPost::model()->findByPk($line[0]);
			$preproc = PreProcPost2::model()->findByPk($line[0]);
				
			if(isset($post) && isset($line[2]) && isset($line[3]) && isset($line[4]) ){
	
				$line[2] = str_replace(" ", "", $line[2]);
				$line[3] = str_replace(" ", "", $line[3]);
				$line[4] = str_replace(" ", "", $line[4]);
					
				$line[2] = trim($line[2]);
				$line[3] = trim($line[3]);
				$line[4] = trim($line[4]);
	
				if($line[2] == $line[3] && $line[2] == $line[4]){
					
					$post->post_type_l3 = $line[2];
					$preproc->post_type_l3 = $line[2];
	
					$post->save();
					$preproc->save();
				}
					
	
			}
				
		}
	
		fclose($myfile);
	
	}
	
	
	public function actionCreateFileToValidate(){
			
		$criteria = new CDbCriteria;
		$criteria->condition = " post_type like '%baseline%' order by rand()";
		
		$path = "C:/Users/fabio/Documents/baseline/campioni da validare.csv";
		$myfile = fopen($path, 'w') or die("Unable to open file!");
		
		$posts = FbPost::model()->findAll($criteria);
		
		foreach ($posts as $p){
			
			$message = str_replace("\n", " ", $p->message);
			$message = str_replace(";", " ", $message);
			
			$type = str_replace("_baseline", "", $p->post_type);
			
			$txt = $p->fbpid.";".$message.";".$type."\n";
			
			fwrite($myfile, $txt);
		}
		
		echo count($posts);
		fclose($myfile);
	}
	
	
	public function actionCreateFileBaseline(){
		
		$criteria = new CDbCriteria;
		$criteria->condition = " post_type !='null' and post_type not like '%baseline%' order by post_type";
		
		$path = "C:/Users/fabio/Documents/baseline/baseline da validare.csv";
		$myfile = fopen($path, 'w') or die("Unable to open file!");
				
		$pre_proc_posts = PreProcPost::model()->findAll($criteria);
		
		foreach ($pre_proc_posts as $ppp){

			$post = FbPost::model()->findByPk($ppp->fbpid);

			$message = str_replace("\n", " ", $post->message);
			$message = str_replace(";", " ", $message);
			
			$txt = $post->fbpid.";".$message.";".$ppp->post_type."\n";
			
			fwrite($myfile, $txt);
			
		}
		
		echo count($pre_proc_posts);
		
		fclose($myfile);
		
	}
	
	public function createBaseline(){
		
		set_time_limit(0);
		
		$path2 = "C:/Users/fabio/Documents/baseline/baseline da validare2.csv";
		$myfile2 = fopen($path2, 'w') or die("Unable to open file!");
		
// 		$classes = array("study_group","tvl", "spam", "need_help", "give_exam_info", "need_exam_info");
		$classes = array("study_group","give_exam_info", "spam");
		
		foreach($classes as $c){
			
			$path = "C:/Users/fabio/Documents/baseline/".$c. ".txt";
			$myfile = fopen($path, 'r') or die("Unable to open file!");
	// 		echo fread($myfile,filesize("webdictionary.txt"));
			
			$i = 1;
			$line = fgets($myfile);
			$count_type = 0;
			
			while(!feof($myfile) && $count_type <= 100){
				
// 				$criteria_type = new CDbCriteria;
// 				$criteria_type->condition = " post_type =".$c;
				
// 				$n_type = PreProcPost::model()->findAllByAttributes(array("post_type" => $c));
				
												
				$line = fgets($myfile);
				$line = str_replace(";", " ", $line);
				$line = explode(" ", $line);
				
				var_dump($line);?> <br><?php
				
				if(isset($line[0])){
					$criteria = new CDbCriteria;
					$criteria->condition = " post_type ='null' and message like '%".$line[0]."%' order by rand() limit 15";
					
			
					$posts = PreProcPost::model()->findAll($criteria);
					$count_type += count($posts);
					
					foreach ($posts as $p ){
						
						$p->post_type = $c;
						echo $p->fbpid . " ". $c. " ". $line[0]; ?> <br><?php
						
						$post = FbPost::model()->findByPk($p->fbpid);
						$message = str_replace("\n", " ", $post->message);
						$message = str_replace(";", " ", $message);
							
						$txt = $post->fbpid.";".$message."\n";
						
						fwrite($myfile2, $txt);
						
// 						$p->save();
					}
						
				
					
				}
			}
			
				fclose($myfile);
				
		}
		fclose($myfile2);
	}
	
	
	public function updateBaseline(){
		
		$criteria=New CDbCriteria;
		$criteria->condition=" post_type is not null";
		
		$post = FbPost::model()->findAll($criteria);
		
		foreach($post as $p){
			
			$p->post_type = $p->post_type."_baseline";
			$p->save(); 
		}
		echo "ok";
		
	}
	
	
	public function featuresRappresentation(){
		
		set_time_limit(0);
		
		$criteria=New CDbCriteria;
		$criteria->condition=" post_type_l1 is null";
			
	   $post = FbPost::model()->findAll($criteria);
		
		
		foreach ($post as $p ){
			
			$features_rappresentation = new FeaturesRappresentation();
			
			$features_rappresentation->pid = $p->fbpid; 
			
			$message = strtolower($p->message);
			
			$features_rappresentation->format_info = substr_count($message,"\n");
			
			$features_rappresentation->n_char = strlen($message);
			
			$comments = FbPostComment::model()->findAllByAttributes(array('ref_entity_id' => $p->fbpid));
			
			$features_rappresentation->n_comment = count($comments);

			
			$comment_char_lenght = 0;
			$n_like_comment = 0;
			
			$top_author_comment = "false";
			
			foreach ($comments as $c ){
				
				$comment_char_lenght += strlen($c->message);
				
				$n_like_comment += $c->like_count;
				
				$author_comment = Users::model()->findByPk($c->author_id);
				
				if($author_comment->user_type == "top")
					$top_author_comment = "true";
				
				 
			}
			
			$features_rappresentation->comment_author_top = $top_author_comment;
			
			$features_rappresentation->n_char_comment_length = $comment_char_lenght;
			
			if($features_rappresentation->comment_author_top != 0 && $features_rappresentation->n_char_comment_length != 0 )
				$features_rappresentation->n_char_comment_length_ratio = round($comment_char_lenght/$features_rappresentation->n_comment, 4);
			else
				$features_rappresentation->n_char_comment_length_ratio = 0;
			
			$features_rappresentation->n_like_comment = $n_like_comment;
			
			if($p->link != null)
				$features_rappresentation->link_presence = "true";
			else  
				$features_rappresentation->link_presence = "false";
			
			
			if($p->application != null){
				
				$features_rappresentation->device = "mobile";
			}else
				$features_rappresentation->device = "web";
			
			$photo = PhotoFbPost::model()->findAllByAttributes(array("element_id" => $p->fbpid));
			
			if($photo != null){
				$features_rappresentation->picture_presence = "true";
			}else 
				$features_rappresentation->picture_presence = "false";
			
			
			$like = LikeFbPost::model()->findAllByAttributes(array("ref_entity_id" => $p->fbpid));
			
			$features_rappresentation->n_like = count($like);
			
			$top_user_like = "false";
			
			foreach ($like as $l){
				
				$author_like = Users::model()->findByPk($l->user_id);
				
					if($author_like->user_type == "top"){
						$top_user_like = "true";
						break;
					}
				
			}
			
			$features_rappresentation->like_top_user = $top_user_like;
								
			$top_author = "false";
			
			$author_post =  Users::model()->findByPk($p->author_id);
			
			if($author_post->user_type == "top")
				$features_rappresentation->top_author_user = "true";
			else 
				$features_rappresentation->top_author_user = "false";
						
			
			$features_rappresentation->question_mark_presence = substr_count($message, "?");
			
			
			$features_rappresentation->math_symbols_presence = $this->checkMathSymbol($message);
			
			$features_rappresentation->time_element_presence = $this->checkTemporalElement($message);
			
			
			
			
			$features_rappresentation->save();
			
								
		}
		
		echo 'OK ;)';
		
	}
	
	
	private function checkMathSymbol($message){
		
		$symbols = array("+", "-", "*", "/", "{", "}", "=", "(", ")", ">", "<", "1","2","3","4","5", "6", "7", "8", "9", "0", "^", "°", "|");
		
		$n_symbols = 0;
		
		foreach( $symbols as $s){
			
			$n_symbols += substr_count($message, $s);
		}
				
		return $n_symbols;
	}
	
	private function checkTemporalElement($message){
		
		$temporal_elements = array("oggi", "ieri","domani", "settimana", "mese", "luned", "marted", "mercoled", "gioved", "venerd", "sabato", "domenica", "gennaio",
									"febbraio", "marzo", "aprile", "maggio", "giugno", "luglio", "agosto", "settembre", "ottobre", "novembre","dicembre");
		
		$n_temporal_elements = 0;
		foreach($temporal_elements as $t){
			
			$n_temporal_elements += substr_count($message , $t);
		
		}
		
		return $n_temporal_elements;
	}
	
	
}

?>
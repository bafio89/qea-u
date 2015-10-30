<?php 

Yii::import('application.modules.users.*');
Yii::import('application.modules.users.models.UserPersonalInfo');
Yii::import('application.modules.users.models.Users');
Yii::import('application.modules.degree.models.Degree');
Yii::import('application.modules.courses.models.Courses');
Yii::import('application.modules.courses.models.Categories.*');

class PostCategorizationController extends Controller
{


	public function ActionIndex(){
		$this->ActionKld();
	}
		
	public function ActionWriteSample(){
		$myfile = fopen('C:\Users\fabio\Documents\campioni categorie.csv', 'w') or die("Unable to open file!");
		fwrite($myfile,"\xEF\xBB\xBF");
		fwrite($myfile, "TESTO DEL POST; LINK; ETICHETTA\n");
		$post = FbPost::model()->findAllByAttributes(array(),"post_type !='' order by post_type");
	
		
		foreach ($post as $p){
			
			$p->message = str_replace("\n",' ' ,$p->message);
		
			$message = '';
			$message = $p->message;
			
			$link = '';
			$link = $p->link;
			
			$post_type = '';
			$post_type = $p->post_type;
			
			$txt = '';
			$txt = $txt. $message.';'.$link.';'.$post_type."\n";
			
			fwrite($myfile,$txt);
		}
		fclose($myfile);
	}
	
	public function ActionKld(){
		
		$categories = array("need_help", "tvl" , "spam", "give_exam_info","need_exam_info", "study_group");
		
		$categories_words = array();
		$single_words = array("need_help" => array(), "tvl" => array() , "spam" => array(), "give_exam_info" => array(), "need_Exam_info" => array() , "study_group" => array());
		$word = array();
		$vocabulary = array();
		$add = 1;
		$i = 0 ;
			

		
		foreach ($categories as $c){
// 			if($c =="need_help"){
// 				$posts1 = PreProcPost::model()->findAllByAttributes(array("post_type" => $c),array("limit" => "10"));
// 				$posts2 = PreProcPost::model()->findAllByAttributes(array("post_type" => "need_exam_info"), array("limit" => "10"));
				
// 				$posts = array_merge($posts1, $posts2);
				
				
// 			}
				$posts = PreProcPost::model()->findAllByAttributes(array("post_type" => $c));
				$categories_words[$c] = "";
			foreach ($posts as $p){
				$categories_words[$c] = $categories_words[$c]. $p->message. " ";
				
			}			
			
			$categories_words[$c] = str_replace("?", "", $categories_words[$c]);
			$categories_words[$c] = trim($categories_words[$c]);
			$tmp = explode(" ", $categories_words[$c]);
					
			
			$word[$tmp[0]] = 0;
			
		
			$single_words[$c][$tmp[0]]["in_oc"] = 0;
			
			foreach ($tmp as $t){
				
				if(!array_key_exists($t, $vocabulary)){
				
					$vocabulary[$t] = true;
				}

					if(isset($single_words[$c][$t])){
						$single_words[$c][$t]["in_oc"] = $single_words[$c][$t]["in_oc"] + 1 ;					
						
						
					}else{
												
						$single_words[$c][$t]["in_oc"] = 1;
						
					}

			}
				
		}

		

		foreach (array_keys($vocabulary) as $t){
			
			foreach ($categories as $c){
				if(array_key_exists($t, $single_words[$c])){
					foreach ($single_words as $sw){
						
						if(array_diff_key($sw ,$single_words[$c]) != null ){							
							if(!isset($single_words[$c][$t]["other_oc"] )){
								$single_words[$c][$t]["other_oc"] = 0;
							}
							if(array_key_exists($t, $sw))
								$single_words[$c][$t]["other_oc"] = $single_words[$c][$t]["other_oc"] + $sw[$t]["in_oc"];
						}
					}			
					
					
				}
				
				
			}
			
		}

	
		$tot = 0;
	 		
		foreach ($categories as $c){
			
	 		foreach ($single_words[$c] as $sw){
	 			
	 			$tot = $tot + $sw["in_oc"];
	 			
	 		}
	 		$single_words[$c]["n_tot"] = $tot;
	 		$tot = 0;
		}
		
		foreach ($categories as $c){
			
			foreach (array_keys($single_words[$c]) as $sw){
				
				if($sw != "n_tot"){
					$prior_probability = ($single_words[$c][$sw]["in_oc"] + 1) / ($single_words[$c]["n_tot"] + count($vocabulary));
					
					$single_words[$c][$sw]["prior_prob"] = round($prior_probability, 5);
				
				}
			}
			
			
		}
		
		
		
		
		//complementary probability
		$total_occurrence = 0;
		
		foreach ($categories as $c){
			
			$total_occurrence = $total_occurrence + $single_words[$c]["n_tot"];
			
		}
		
		foreach ($categories as $c){
			
			$single_words[$c]["other_tot"] = $total_occurrence - $single_words[$c]["n_tot"];
			
		}
		
		
		
		foreach ($categories as $c){
				
			foreach (array_keys($single_words[$c]) as $sw){
		
				if($sw != "n_tot" && $sw != "other_tot"){
					$complementary_probability = ($single_words[$c][$sw]["other_oc"] + 1) / ($single_words[$c]["other_tot"] + count($vocabulary));
						
					$single_words[$c][$sw]["complementary_prob"] = round($complementary_probability, 5);
		
				}
			}
				
				
		}
		
		
		//KLD
		foreach ($categories as $c){
		
			foreach (array_keys($single_words[$c]) as $sw){
				
				if($sw != "n_tot" && $sw != "other_tot"){
					$rapporto = $single_words[$c][$sw]["prior_prob"] / $single_words[$c][$sw]["complementary_prob"];
					$kld  = $single_words[$c][$sw]["prior_prob"] * log($rapporto);
					
					$single_words[$c][$sw]["kld"] = round($kld, 5) ;
				}
			}
		}
		
		
		$study_group = array();
		$tvl = array();
		$spam = array();
		$need_exam_info = array();
		$need_help = array();
		$give_exam_info = array();
		
		foreach (array_keys($single_words["tvl"]) as $sw){
			
			$tvl[$sw] = $single_words["tvl"][$sw]["kld"];
		}
		
		foreach (array_keys($single_words["spam"]) as $sw){
				
			$spam[$sw] = $single_words["spam"][$sw]["kld"];
		}
		foreach (array_keys($single_words["need_exam_info"]) as $sw){
				
			$need_exam_info[$sw] = $single_words["need_exam_info"][$sw]["kld"];
		}
		foreach (array_keys($single_words["need_help"]) as $sw){
				
			$need_help[$sw] = $single_words["need_help"][$sw]["kld"];
		}
		foreach (array_keys($single_words["give_exam_info"]) as $sw){
				
			$give_exam_info[$sw] = $single_words["give_exam_info"][$sw]["kld"];
		}
		foreach (array_keys($single_words["study_group"]) as $sw){
				
			$study_group[$sw] = $single_words["study_group"][$sw]["kld"];
		}
		
		arsort($tvl);
		arsort($spam);
		arsort($give_exam_info);
		arsort($need_exam_info);
		arsort($need_help);
		arsort($study_group);
		
// 		 var_dump($tvl);
		 	 
		 // write tvl
		 $myfile = fopen('C:\Users\fabio\Google Drive\TESI - Q&A SL\TESI 2.0\Classificazione\KLD - keys\6 classi\tvl keys 6.csv', 'w') or die("Unable to open file!");
		 fwrite($myfile,"\xEF\xBB\xBF");
		 fwrite($myfile, "WORD; KLD - Value\n");
		 
		 foreach(array_keys($tvl) as $key){
		 	
		 	$text = $key.";".$tvl[$key]."\n";
		 	
		 	fwrite($myfile, $text);
		 }
		 fclose($myfile);

		 
		 // write need_help
		 $myfile = fopen('C:\Users\fabio\Google Drive\TESI - Q&A SL\TESI 2.0\Classificazione\KLD - keys\6 classi\need_help keys 6.csv', 'w') or die("Unable to open file!");
		 fwrite($myfile,"\xEF\xBB\xBF");
		 fwrite($myfile, "WORD; KLD - Value\n");
		 	
		 foreach(array_keys($need_help) as $key){
		 
		 	$text = $key.";".$need_help[$key]."\n";
		 
		 	fwrite($myfile, $text);
		 }
		 fclose($myfile);
		 
		 
		 // write need_exam_info
		 $myfile = fopen('C:\Users\fabio\Google Drive\TESI - Q&A SL\TESI 2.0\Classificazione\KLD - keys\6 classi\need_exam_info keys 6.csv', 'w') or die("Unable to open file!");
		 fwrite($myfile,"\xEF\xBB\xBF");
		 fwrite($myfile, "WORD; KLD - Value\n");
		 	
		 foreach(array_keys($need_exam_info) as $key){
		 
		 	$text = $key.";".$need_exam_info[$key]."\n";
		 
		 	fwrite($myfile, $text);
		 }
		 fclose($myfile);
		 
		 
		 // write study_group
		 $myfile = fopen('C:\Users\fabio\Google Drive\TESI - Q&A SL\TESI 2.0\Classificazione\KLD - keys\6 classi\study_group keys 6.csv', 'w') or die("Unable to open file!");
		 fwrite($myfile,"\xEF\xBB\xBF");
		 fwrite($myfile, "WORD; KLD - Value\n");
		 	
		 foreach(array_keys($study_group) as $key){
		 
		 	$text = $key.";".$study_group[$key]."\n";
		 
		 	fwrite($myfile, $text);
		 }
		 fclose($myfile);
		 
		 
		 
		 // write spam
		 $myfile = fopen('C:\Users\fabio\Google Drive\TESI - Q&A SL\TESI 2.0\Classificazione\KLD - keys\6 classi\spam keys 6.csv', 'w') or die("Unable to open file!");
		 fwrite($myfile,"\xEF\xBB\xBF");
		 fwrite($myfile, "WORD; KLD - Value\n");
		 	
		 foreach(array_keys($spam) as $key){
		 
		 	$text = $key.";".$spam[$key]."\n";
		 
		 	fwrite($myfile, $text);
		 }
		 fclose($myfile);
		 
		 
		 // write give_exam_info
		 $myfile = fopen('C:\Users\fabio\Google Drive\TESI - Q&A SL\TESI 2.0\Classificazione\KLD - keys\6 classi\give_exam_info keys 6.csv', 'w') or die("Unable to open file!");
		 fwrite($myfile,"\xEF\xBB\xBF");
		 fwrite($myfile, "WORD; KLD - Value\n");
		 	
		 foreach(array_keys($give_exam_info) as $key){
		 
		 	$text = $key.";".$give_exam_info[$key]."\n";
		 
		 	fwrite($myfile, $text);
		 }
		 fclose($myfile);
		 
		 
		 
	}
	
	public function priorProbability(){
		
		
		
	}
	
	
	public function countClassOccurrence(){
		
	}
	
	public function countTotalOccurrence(){
		
	}
	
}


?>
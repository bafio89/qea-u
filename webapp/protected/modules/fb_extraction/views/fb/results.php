<div class="row">
<div class="col-lg-3"></div>

<div class="col-lg-6">
<h2 style="text-align: center">Risultati</h2>

<br>

<h4>Ho estratto:</h4>

<?php    
	
		$ref_id = ''; 
		$total_comment = array();
		
		$total_post_exam = array();
		$total_degree_post_comment = array();
		$total_photos_degree_post = array();
		$total_like_exam_comments = array();
		
		$total_photos_comment_degree = array();
		$total_posts_exam = array();
		$total_posts_exam_like = array();
		$total_photo_post_exam = array();
		$total_exam_post_comment = array();
		$total_photo_exam_comments = array();
		$total_like_docs_degree = array();
		$total_like_comment_degree = array();
		$total_like_post_degree = array();
		
		$n_photos_posts_degree = 0;
		
		$n_like_post_degree = 0;
		
		$n_total_like_docs = 0;
		$n_like_comment_degree = 0;
		$n_comment_post_degree = 0;
		$n_photos_comment_degree = 0;
		$n_files_degree = 0;
		$n_docs_degree = 0;
		$n_post_degree = 0;
		$n_member = 0;
		
		
		$n_exam_group_created = 0;
		$n_total_post_exam = 0;
		$n_total_post_exam_like = 0;
		$n_total_photo_post_exam = 0;
		$n_total_exam_post_comment = 0;
		$n_total_photo_exam_comments = 0;
		$n_total_like_comments = 0;
		
		$n_total_exam_files = 0;
		$n_total_exam_docs = 0;
		
	if(is_a($group, 'DegreeGroup')){
		
		$ref_id = $group->cid;
		
		$find_group = DegreeGroup::model()->findByPk($group->cid);
		
		$posts_degree = FbPost::model()->findAllByAttributes(array('degree_group_id'=> $group->cid));
		
		$docs_degree = FbDoc::model()->findAllByAttributes(array('degree_group_id'=> $group->cid));
		
		$files_degree = FbFiles::model()->findAllByAttributes(array('degree_group_id' => $group->cid));

		foreach ($docs_degree as $doc){
			
			$docs_like =  LikeFbDoc::model()->findByAttributes(array('ref_entity_id' => $doc->fbdid));
			
			$n_total_like_docs += count($docs_like);

// 			array_push($total_like_docs_degree, $docs_like);
			 
		}
					
		$exam_group_created = ExamGroup::model()->findAllByAttributes(array('degree_group_id' => $group->cid));		
		
		$member = MemberDegreeGroup::model()->findAllByAttributes(array('user_group_id' => $group->cid));		
		
		foreach ($posts_degree as $p){
			
			$photos = PhotoFbPost::model()->findAllByAttributes(array('element_id'=>$p->fbpid));
			
			$n_photos_posts_degree += count($photos);
// 			array_push($total_photos_degree_post, $photos);
			
			$like_post_degree = LikeFbPost::model()->findAllByAttributes(array('ref_entity_id' => $p->fbpid));
			
			$n_like_post_degree += count($like_post_degree);
// 			array_push($total_like_post_degree, $like_post_degree);
			
				
				$comments = FbPostComment::model()->findAllByAttributes(array('ref_entity_id' => $p->fbpid));
				
			
				$n_comment_post_degree += count($comments);
// 				array_push($total_degree_post_comment, $comments);
				
				foreach ($comments as $c){
					
					$photo_comments_degree_posts = PhotoFbCommentPost::model()->findByAttributes(array('element_id' => $c->cid));
					
					$n_photos_comment_degree += count($photo_comments_degree_posts);
// 					array_push($total_photos_comment_degree, $photo_comments_degree_posts);
					
					$like_comment_degree = LikeFbCommentPost::model()->findAllByAttributes(array('ref_entity_id' => $c->cid));
					
					$n_like_comment_degree += $c->like_count;
					
					
// 					array_push($total_like_comment_degree, $limit_comment_degree);
				}
		
		}
		
		
// 		$n_photos_posts_degree = count($photos);
// 		$n_like_post_degree = count($total_like_post_degree);
				
// 		$n_total_like_docs = count($total_like_docs_degree);
// 		$n_like_comment_degree = count($total_like_comment_degree);
// 		$n_comment_post_degree = count($total_degree_post_comment);
// 		$n_photos_comment_degree = count($total_photos_comment_degree);
		$n_files_degree = count($files_degree);
		$n_docs_degree = count($docs_degree);
		$n_post_degree = count($posts_degree);
		$n_member = count($member);
?>
		<label>Gruppo: <?php echo $find_group->name?></label><br>
		<label>Ha <?php echo $n_member?> membri</label><br>
		<label>Numero di post: <?php echo $n_post_degree ?></label> <br>
		<label>Numero di like ai post: <?php echo $n_like_post_degree ?> </label><br>
		<label>Numero di commenti ai post: <?php echo $n_comment_post_degree?></label><br>
		<label>Numero di like ai commenti: <?php echo $n_like_comment_degree?></label><br>
		<label>Numero di files: <?php echo $n_files_degree?></label> <br>
		<label>Numero di documenti: <?php echo $n_docs_degree?></label> <br>
		<label>Numero di like ai doc <?php echo $n_total_like_docs?></label><br>
		<label>Numero di foto nei post: <?php echo $n_photos_posts_degree ?></label> <br>
		<label>Numero di foto nei commenti: <?php echo $n_photos_comment_degree ?></label>
	
		
<?php
		$docs_exam = array();
		$files_exam = array();
		
		foreach($exam_group_created as $ec){

			$posts_exam = FbPost::model()->findAllByAttributes(array('exam_group_id' => $ec->eid));
			
			$n_total_post_exam += count($posts_exam);
						
// 			array_push($total_post_exam, $posts_exam);

			$docs_exam = FbDoc::model()->findAllByAttributes(array('degree_group_id'=> $ec->eid));
			
			$files_exam = FbFiles::model()->findAllByAttributes(array('degree_group_id' => $ec->eid));
			
			foreach($posts_exam as $pe){
				
				$posts_exam_like = LikeFbPost::model()->findAllByAttributes(array('ref_entity_id' => $pe->fbpid));
				
				$n_total_post_exam_like += count($posts_exam_like);

// 				array_push($total_posts_exam_like, $posts_exam_like);
				
				$photos_exam_post = PhotoFbCommentPost::model()->findAllByAttributes(array('element_id' => $pe->fbpid));
				
				$n_total_photo_post_exam += count($photos_exam_post);

// 				array_push($total_photo_post_exam, $photos_exam_post);
				
				$comments = FbPostComment::model()->findAllByAttributes(array('ref_entity_id' => $pe->fbpid));
				
				

// 				array_push($total_exam_post_comment, $comments);
				
				
				
				foreach($comments as $c){
					
					array_push($total_exam_post_comment, $c);
					
					$photo_exam_comments = PhotoFbCommentPost::model()->findAllByAttributes(array('element_id' => $c->cid));
					
					$n_total_photo_exam_comments += count($photo_exam_comments);
// 					array_push($total_photo_exam_comments, $photo_exam_comments);
										
					$comment_exam_like = LikeFbCommentPost::model()->findAllByAttributes(array('ref_entity_id' => $c->cid));
					
					$n_total_like_comments += $c->like_count;
					
// 					array_push($total_like_exam_comments, $comment_exam_like);
					
				}
			}
			
		}
		
		$n_exam_group_created = count($exam_group_created);
// 		$n_total_post_exam = count($total_post_exam);
// 		$n_total_post_exam_like = count($total_posts_exam_like);
// 		$n_total_photo_post_exam = count($total_photo_post_exam);
		$n_total_exam_post_comment = count($total_exam_post_comment);		
// 		$n_total_photo_exam_comments = count($total_photo_exam_comments);
// 		$n_total_like_comments = count($total_like_exam_comments);
		
		$n_total_exam_files = count($files_exam);
		$n_total_exam_docs = count($docs_exam);
		
		?>
		<br>
				<label>N. Gruppi Esame(TAG): <?php echo $n_exam_group_created ?></label><br>
				
				<label>Numero di post: <?php echo $n_total_post_exam ?></label> <br>
				<label>Numero di like ai post: <?php echo $n_total_post_exam_like ?> </label><br>
				<label>Numero di commenti ai post: <?php echo $n_total_exam_post_comment?></label><br>
				<label>Numero di like ai commenti: <?php echo $n_total_like_comments ?></label><br>
				<label>Numero di files: <?php echo $n_total_exam_files ?></label> <br>
				<label>Numero di documenti: <?php echo $n_total_exam_docs?></label> <br>				
				<label>Numero di foto nei post: <?php echo $n_total_photo_post_exam ?></label> <br>
				<label>Numero di foto nei commenti: <?php echo $n_total_photo_exam_comments ?></label>
				
				
<?php 		
		
	}else{
		
			$ref_id = $group->eid;
		
			$exam = ExamGroup::model()->findByPk($ref_id);
		
			$posts_exam = FbPost::model()->findAllByAttributes(array('exam_group_id' => $ref_id));
			
			$exam_member = MemberExamGroup::model()->findAllByAttributes(array('user_group_id' => $ref_id));
// 			array_push($total_post_exam, $posts_exam);
		
			$docs_exam = FbDoc::model()->findAllByAttributes(array('exam_group_id'=> $ref_id));
				
			$files_exam = FbFiles::model()->findAllByAttributes(array('exam_group_id' => $ref_id));
				
			foreach($posts_exam as $pe){
		
				$posts_exam_like = LikeFbPost::model()->findAllByAttributes(array('ref_entity_id' => $pe->fbpid));
		
// 				array_push($total_posts_exam_like, $posts_exam_like);

				$n_total_post_exam_like += count($posts_exam_like);
		
				$photos_exam_post = PhotoFbPost::model()->findAllByAttributes(array('element_id' => $pe->fbpid));
		
// 				array_push($total_photo_post_exam, $photos_exam_post);
				
				$n_total_photo_post_exam += count($photos_exam_post);
		
				$comments = FbPostComment::model()->findAllByAttributes(array('ref_entity_id' => $pe->fbpid));
								
					
		
				foreach($comments as $c){
					
					array_push($total_exam_post_comment, $c);
					
					$photo_exam_comments = PhotoFbCommentPost::model()->findAllByAttributes(array('element_id' => $c->cid));

					$n_total_photo_exam_comments += count($photo_exam_comments);
					
// 					array_push($total_photo_exam_comments, $photo_exam_comments);
		
					$comment_exam_like = LikeFbCommentPost::model()->findAllByAttributes(array('ref_entity_id' => $c->cid));
						
					$n_total_like_comments += $c->like_count;
// 					array_push($total_like_exam_comments, $comment_exam_like);
						
				}
			}
				
		
	
		$n_total_post_exam = count($posts_exam);
// 		$n_total_post_exam_like = count($total_posts_exam_like);
// 		$n_total_photo_post_exam = count($total_photo_post_exam);
		$n_total_exam_post_comment = count($total_exam_post_comment);
// 		$n_total_photo_exam_comments = count($total_photo_exam_comments);
// 		$n_total_like_comments = count($total_like_exam_comments);
		
		$n_member = count($exam_member);
		$n_total_exam_files = count($files_exam);
		$n_total_exam_docs = count($docs_exam);
	?>
		
		<label>Gruppo Esame: <?php echo $exam->name ?></label><br>
		<label>Ci sono <?php echo $n_member ?> membri</label><br>
		
		<label>Numero di post: <?php echo $n_total_post_exam ?></label> <br>
		<label>Numero di like ai post: <?php echo $n_total_post_exam_like ?> </label><br>
		<label>Numero di commenti ai post: <?php echo $n_total_exam_post_comment?></label><br>
		<label>Numero di like ai commenti: <?php echo $n_total_like_comments ?></label><br>
		<label>Numero di files: <?php echo $n_total_exam_files ?></label> <br>
		<label>Numero di documenti: <?php echo $n_total_exam_docs?></label> <br>				
		<label>Numero di foto nei post: <?php echo $n_total_photo_post_exam ?></label> <br>
		<label>Numero di foto nei commenti: <?php echo $n_total_photo_exam_comments ?></label>
	<?php 
	}
	
	
	
	
		

?>


</div>
<div class="col-lg-3"></div>

</div>

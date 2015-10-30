<?php 
$this->pageTitle = Yii::t('ProfileModule.view', 'page.title', array('{nickname}' => $user->nickname));
?>

<h1><?php echo $user->nickname ?></h1>

<?php if (Yii::app()->user->getFlash('success')): ?>
	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php echo Yii::t('ProfileModule.view', 'page.editProfile.success.description') . '<br>'; ?>
		<?php
			$pending = Yii::app()->user->getFlash('pending');
			
			switch ($pending) {
				case 'email':
					echo Yii::t('ProfileModule.view', 'page.editProfile.changedEmail.description', array('{email_address}' => $user->email));
					break;
				case 'password':
					echo Yii::t('ProfileModule.view', 'page.editProfile.changedPassword.description');
					break;
			}
		?>
	</div>
<?php endif; ?>

<p>
	Customize your profile layout here.
</p>

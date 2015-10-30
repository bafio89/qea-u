
<?php foreach ($providers as $p): ?>

	<?php if ($p == ProviderManager::FACEBOOK): ?>
		<a href="<?php echo Yii::app()->createUrl('users/auth/social?provider='.ProviderManager::FACEBOOK) ?>"><i class="fa fa-facebook-square" style="font-size: 40px; color: #026;"></i></a>
	<?php endif; ?>
	
	<?php if ($p == ProviderManager::GOOGLE): ?>
		<a href="<?php echo Yii::app()->createUrl('users/auth/social?provider='.ProviderManager::GOOGLE) ?>"><i class="fa fa-google-plus-square" style="font-size: 40px; color: #c00;"></i></a>
	<?php endif; ?>
	
	<?php if ($p == ProviderManager::TWITTER): ?>
		<a href="<?php echo Yii::app()->createUrl('users/auth/social?provider='.ProviderManager::TWITTER) ?>"><i class="fa fa-twitter-square" style="font-size: 40px; color: #09f;"></i></a>
	<?php endif; ?>
	
<?php endforeach; ?>

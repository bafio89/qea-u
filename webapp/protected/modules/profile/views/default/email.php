<?php
$this->pageTitle = Yii::t('ProfileModule.email', 'page.title');
?>
<h1><?php echo Yii::t('ProfileModule.email', 'page.title') ?></h1>
<p>
	<?php if ($success): ?>
		<?php echo Yii::t('ProfileModule.email', 'page.confirmation.success') ?>
	<?php else: ?>
		<?php echo Yii::t('ProfileModule.email', 'page.confirmation.failure') ?>
	<?php endif; ?>
</p>

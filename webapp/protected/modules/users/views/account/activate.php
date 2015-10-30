<?php
/* @var $this AccountController */
$this->pageTitle = Yii::t('UsersModule.activate', 'page.title');
?>
<h1><?php echo Yii::t('UsersModule.activate', 'page.title') ?></h1>
<p>
	<?php if ($success): ?>
		<?php echo Yii::t('UsersModule.activate', 'page.activation.success') ?>
	<?php else: ?>
		<?php echo Yii::t('UsersModule.activate', 'page.activation.failure') ?>
	<?php endif; ?>
</p>

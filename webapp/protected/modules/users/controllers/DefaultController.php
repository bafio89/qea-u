<?php


class DefaultController extends Controller
{
	
	/**
	 * Redirects to authentication.
	 */
	public function actionIndex()
	{
		$this->redirect(
			Yii::app()->createUrl('users/auth')
		);
	}
	
}

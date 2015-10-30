<?php

/**
 * Implement this interface when you have to write a simple notifier.
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
interface IBasicNotifier
{

	public static function sendEmail($to, $subject, $html_body, $from = null, $notification_time = 0);

	public static function sendTemplatedEmail($to, $subject, $tpl_name, $tpl_data, $tpl_lang, $from = null, $notification_time = 0);
	
}

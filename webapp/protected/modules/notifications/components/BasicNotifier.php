<?php

Yii::import('ext.phpMailer.QPhpMailer');

/**
 * This is a basic notifier.
 * 
 * Currently just email notifications are implemented, using the PHPMailer library.
 * You should use that library also if you're already using email providers (such as ElasticMail),
 * wich usually support totally SMTP.
 * 
 * @see http://elasticemail.com/api-documentation/smtp-relay
 * @see https://github.com/PHPMailer/PHPMailer/blob/master/examples/smtp.phps
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 */
class BasicNotifier extends CComponent implements IBasicNotifier
{
	
	/**
	 * Send a HTML string (message) via mail without templates. 
	 * Tipical usage:
	 * 
	 * <pre>
	 * BasicNotifier::sendEmail('mario.rossi@gmail.com', 'Welcome new user!', '<h1>Welcome!</h1>');
	 * </pre>
	 * 
	 * @param unknown $to
	 * @param unknown $subject
	 * @param unknown $html_body
	 * @param string $from
	 * @param number $notification_time
	 */
	public static function sendEmail($to, $subject, $html_body, $from = null, $notification_time = 0)
	{
		if (!empty($to) && !empty($subject) && !empty($html_body)) {
			$mail = new QPhpMailer();
			$mail->IsSendmail();
			$mail->CharSet = 'utf-8';
			
			if (isset($from))
				$mail->SetFrom($from);
			else
				$mail->SetFrom(Yii::app()->params['EmailNoReply']);
			
			$mail->AddAddress($to);
			$mail->Subject = $subject;
			$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
			$mail->MsgHTML($html_body);
			
			$mail->Send();
		}
	}
	
	/**
	 * This method sends email using templates.
	 * Tipical usage:
	 * 
	 * <pre>
	 * BasicNotifier::sendTemplatedEmail('mario.rossi@gmail.com', 'Welcome new user!', 'users/account_created', array(
	 *     '{USER_FIRST_NAME}' => 'Mario'
	 * ), 'it');
	 * </pre>
	 * 
	 * @param unknown $to
	 * @param unknown $subject
	 * @param unknown $tpl_name
	 * @param unknown $tpl_data
	 * @param unknown $tpl_lang
	 * @param string $from
	 * @param number $notification_time
	 * @throws Exception If can't send this email.
	 */
	public static function sendTemplatedEmail($to, $subject, $tpl_name, $tpl_data, $tpl_lang = 'en', $from = null, $notification_time = 0)
	{	
		if (!empty($tpl_name) && !empty($tpl_lang)) {
			$email_base_path = Yii::app()->getBasePath() . '/data/emails/';
			$tpl_file = $email_base_path . $tpl_name . '_' . strtolower(substr($tpl_lang, 0, 2)) . '.html';
			
			if (file_exists($tpl_file)) {
				$html_body = file_get_contents($tpl_file);
				
				if (isset($html_body)) {
					if (isset($tpl_data)) {
						foreach ($tpl_data as $k => $v) {
							$html_body = str_replace($k, $v, $html_body);
						}
					}
					
					return self::sendEmail($to, $subject, $html_body, $from, $notification_time);
				}
			}
		}
		
		throw new Exception('Can\'t send this email.');
	}
	
}

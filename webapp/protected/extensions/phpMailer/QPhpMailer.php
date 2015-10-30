<?php

/**
 * QPhpMailer class file.
 * 
 * A typical usage of QPhpMailer is as follows:
 * <pre>
 * Yii::import('ext.phpmailer.QPhpMailer');
 * $mail=new QPhpMailer;
 * $mail->IsSMTP();
 * $mail->Host='smpt.163.com';
 * $mail->SMTPAuth=true;
 * $mail->Username='yourname@yourdomain';
 * $mail->Password='yourpassword';
 * $mail->SetFrom('name@yourdomain.com','First Last');
 * $mail->Subject='PHPMailer Test Subject via smtp, basic with authentication';
 * $mail->AltBody='To view the message, please use an HTML compatible email viewer!';
 * $mail->MsgHTML('<h1>JUST A TEST!</h1>');
 * $mail->AddAddress('whoto@otherdomain.com','John Doe');
 * $mail->Send();
 * </pre>
 * 
 * @author Niccolò Ciardo <contact@nciardo.com>
 * @required PHPMailer v5.2.7
 */

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'class.phpmailer.php';

class QPhpMailer extends PHPMailer
{
	// ...
}

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function email($to, $subject, $message, $replyto = '')
{
    $CI = &get_instance();
    $CI->load->library('mailer');

    $mail = $CI->mailer;
	$mail->CharSet = 'utf-8';

	$mail->IsSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'email-smtp.us-east-1.amazonaws.com';  // Specify main and backup server
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'AKIAJAYMMD3JDSACPVKA';                            // SMTP username
	$mail->Password = 'AtqryFwgmE2NXTpwho6ibODiH9unnlEj53Q++jITYP/M';                           // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
	$mail->Port = 587;

	$mail->From = 'noreply@orabota.ru';
	$mail->FromName = 'orabota';

    //Set an alternative reply-to address
    if ($replyto != '')
        $mail->AddReplyTo($replyto);

    $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
	$mail->IsHTML(true);                                  // Set email format to HTML

    $mail->AddAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AltBody = strip_tags($message);

    if(!$mail->Send()) 
    {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		return FALSE;
	}

    $mail->ClearAddresses();

    return TRUE;
}

/* End of file email_helper.php */
/* Location: ./application/helpers/email_helper.php */
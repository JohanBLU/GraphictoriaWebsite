<?php
	class mailHandler {
		public static function sendMail($message, $altmessage, $to, $title, $username) {
			include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/libs/mail/PHPMailerAutoload.php';
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = 'pro.turbo-smtp.com';
			$mail->Port = 465;
			$mail->SMTPSecure = 'ssl';
			$mail->SMTPAuth = true;
			$mail->Username = '';
			$mail->Password = '';
			$mail->From = '';
			$mail->FromName = 'Graphictoria';
			$mail->addAddress($to, $username);
			$mail->addReplyTo('support@xdiscuss.net', 'Graphictoria');
			$mail->WordWrap = 50;
			$mail->isHTML(true);
			$mail->Subject = $title;
			$mail->Body    = $message;
			$mail->AltBody = $altmessage;
			$mail->send();
		}
	}
?>

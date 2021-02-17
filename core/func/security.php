<?php
	class security {
		public static function getUserEmailVerified() {
			if ($GLOBALS['userTable']['emailverified'] == 0) {
				return false;
			}else{
				return true;
			}
		}
		
		public static function generateEmailCode() {
			$emailCode = sha1(context::random_str(64));
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET emailverifycode = :newCode WHERE id = :id;");
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->bindParam(':newCode', $emailCode, PDO::PARAM_STR);
			$stmt->execute();
			
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET emailcodeTime = NOW() WHERE id = :id;");
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
		}
		
		public static function liftBan() {
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET banned = 0 WHERE id = :id;");
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET bantype = 0 WHERE id = :id;");
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			
			echo 'success';
			exit;
		}
		
		public static function returnLiftError() {
			echo 'ban-lift-error';
			exit;
		}
		
		public static function getEmailCode() {
			$query = "SELECT emailverifyCode FROM users WHERE id = :id";
			$stmt = $GLOBALS['dbcon']->prepare($query);
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT); 
			$stmt->execute(); 
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return $result['emailverifyCode'];
		}
		
		public static function finishEmailVerification() {
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET emailverified = 1 WHERE id = :id;");
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
			
			$stmt = $GLOBALS['dbcon']->prepare("UPDATE users SET lastUpload = NULL WHERE id = :id;");
			$stmt->bindParam(':id', $GLOBALS['userTable']['id'], PDO::PARAM_INT);
			$stmt->execute();
		}
		
		public static function sendEmailVerificationMessage() {
			security::generateEmailCode();
			mailHandler::sendMail('Your Graphictoria verification code is: '.security::getEmailCode().'<br><br>If you did not request this, you can ignore this message<br><br><a href="http://xdiscuss.net">Graphictoria</a><br>Please know that this message was generated automatically, do not reply to this. If you need help, send a message to <a href="mailto:support@xdiscuss.net">support@xdiscuss.net</a>.', "Your Graphictoria verification code is: ".security::getEmailCode(), $GLOBALS['userTable']['email'], "Graphictoria Email Verification", $GLOBALS['userTable']['username']);
		}
	}
?>
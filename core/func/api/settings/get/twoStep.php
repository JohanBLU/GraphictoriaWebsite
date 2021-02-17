<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/includes.php';
	if ($GLOBALS['loggedIn'] == false) {
		exit;
	}
	include_once $_SERVER['DOCUMENT_ROOT'].'/core/func/libs/google/GoogleAuthenticator.php';
	
	if ($GLOBALS['userTable']['2faEnabled'] == 0 and $GLOBALS['userTable']['2faInit'] == 0) {
		echo '<p>Click the button below to activate two step authentication. You will be asked to test your key before it will be fully enabled.<p>
			<button id="enableTwo" class="btn btn-success">Enable Two Step Authentication</button>';
	}else{
		$gAuth = new GoogleAuthenticator();
		if ($GLOBALS['userTable']['2faEnabled'] == 0 and $GLOBALS['userTable']['2faInit'] == 1) {
			echo '<p>Your secret key is <code>'.$GLOBALS['userTable']['authKey'].'</code></p>';
			echo '<p>You can also use the QR code to add your secret key automatically.</p>';
			echo '<img src="'.$gAuth->getURL($GLOBALS['userTable']['username'], 'xdiscuss.net', $GLOBALS['userTable']['authKey']).'"><br><br>';
			echo '<p>Because you have not yet verified if this works, you will not be asked for a code the next time you login. Please finish the setup.</p>';
			echo '<input type="text" id="finalCode" class="form-control" placeholder="Enter your verification code you have generated here"></input>';
			echo '<button id="enableTwoFinal" onclick="enableTwoFinal()" class="btn btn-primary fullWidth">Finish Two Step Authentication Setup</button>';
		}else{
			echo '<p>Your secret key is <code>'.$GLOBALS['userTable']['authKey'].'</code></p>';
			echo '<p>You can also use the QR code to add your secret key automatically.</p>';
			echo '<img src="'.$gAuth->getURL($GLOBALS['userTable']['username'], 'xdiscuss.net', $GLOBALS['userTable']['authKey']).'"><br>';
			echo '<button id="disableTwo" onclick="disableFactor()" class="btn btn-danger">Disable Two Step Authentication</button>';
		}
	}
?>
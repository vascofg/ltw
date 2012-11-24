<?php
	if(preg_match('/~\w+/',$_SERVER['REQUEST_URI'],$matches)) //if at FEUP (link contains ~<username>)
		session_name($matches[0]."social_news_t5g7");
	else
		session_name("social_news_t5g7");
	session_start();
	function redirect($url) {
		if(!headers_sent()) {
			//If headers not sent yet... then do php redirect
			header('Location: '.$url);
			exit;
		} else {
			//If headers are sent... do javascript redirect... if javascript disabled, do html redirect.
			echo '<script type="text/javascript">';
			echo 'window.location.href="'.$url.'";';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
			echo '</noscript>';
			exit;
		}
	}
	
	function redirectmsg($url, $msg) {
		$_SESSION['msg']=$msg;
		redirect($url);
	}
?>
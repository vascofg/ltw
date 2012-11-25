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
	
	function hasfavorite($news_id, $db) {
		$stmt = $db->prepare('SELECT count(favorite.news_id) as favorite FROM news LEFT JOIN favorite ON news.id=favorite.news_id where news.id=? and favorite.user_id=?');
		if($stmt->execute(array($news_id, $_SESSION['user_id'])))
		{
			$stmt=$stmt->fetch();
			if($stmt['favorite']==0)
				return false;
			return true;
		}
	}
	
	function getuserprofilelink($username, $db) { // if the user exists get the link, else just echo the name
		$stmt = $db->prepare('SELECT id, user_type FROM user WHERE username = :username');
		$stmt->bindparam(':username', $username);
			
		if($stmt->execute())
		{
			$stmt = $stmt->fetchAll();
			
			if(count($stmt)==0) //if no results, the user profile can't be seen
				return $username;
			else
				return "<a href=ver_perfil_utilizador.php?id=".$stmt[0]['id'].">".$username."</a>";
		}
	}
?>
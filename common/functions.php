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
	
	function redirectMsg($url, $msg) {
		$_SESSION['msg']=$msg;
		redirect($url);
	}
	
	function loggedIn() {
		return isset($_SESSION['username']);
	}
	
	function user() {
		return $_SESSION['user_type']==0;
	}
	
	function editor() {
		return $_SESSION['user_type']==1;
	}
	
	function admin() {
		return $_SESSION['user_type']==2;
	}
	
	function hasFavorite($news_id, $db) {
		$stmt = $db->prepare('SELECT count(favorite.news_id) as favorite FROM news LEFT JOIN favorite ON news.id=favorite.news_id where news.id=? and favorite.user_id=?');
		if($stmt->execute(array($news_id, $_SESSION['user_id'])))
		{
			$stmt=$stmt->fetch();
			if($stmt['favorite']==0)
				return false;
			return true;
		}
		return false;
	}
	
	function getUserProfileLink($username, $db) { // if the user exists get the link, else just echo the name
		$stmt = $db->prepare('SELECT id FROM user WHERE username = :username');
		$stmt->bindparam(':username', $username);
			
		if($stmt->execute())
		{
			$stmt = $stmt->fetchAll();
			
			if(count($stmt)==0) //if no results, the user profile can't be seen
				return $username;
			else
				return "<a href=\"ver_perfil_utilizador.php?id=".$stmt[0]['id']."\">".$username."</a>";
		}
		return false;
	}
	
	function isNewsFromUser($news_id, $db)
	{
		$stmt = $db->prepare('SELECT count(*) as count FROM news WHERE id = :news_id and (posted_by = :username OR imported_by = :username)');
		$stmt->bindparam(':news_id', $news_id);
		$stmt->bindparam(':username', $_SESSION['username']);
		if($stmt->execute())
		{
			$stmt = $stmt->fetchAll();
			if($stmt[0]['count']==1)
				return true;
			else
				return false;
		}
		return false;
	}
	
	function isCommentFromUser($comment_id, $db)
	{
		$stmt = $db->prepare('SELECT count(*) as count FROM comment WHERE rowid = :comment_id and user_id = :user_id');
		$stmt->bindparam(':comment_id', $comment_id);
		$stmt->bindparam(':user_id', $_SESSION['user_id']);
		if($stmt->execute())
		{
			$stmt = $stmt->fetchAll();
			if($stmt[0]['count']==1)
				return true;
			else
				return false;
		}
		return false;
	}
	
	function displayDate($date)
	{
		if(date('dmY') == date('dmY', $date)) //if news is from today, display only time, otherwise display date and time
          return "Hoje, ".date('H:i', $date);
        if(date('dmY', time()-86400) == date('dmY', $date)) //yesterday (1 day = 86400 seconds)
          return "Ontem, ".date('H:i', $date);
		switch(date('N',$date))
		{
			case 1:
				$dayofweek = 'Seg';
				break;
			case 2:
				$dayofweek = 'Ter';
				break;
			case 3:
				$dayofweek = 'Qua';
				break;
			case 4:
				$dayofweek = 'Qui';
				break;
			case 5:
				$dayofweek = 'Sex';
				break;
			case 6:
				$dayofweek = 'Sáb';
				break;
			case 7:
				$dayofweek = 'Dom';
				break;
		}
        return $dayofweek." ".date('d/m/Y, H:i', $date);
	}

	function showAllNews($news)
	{
		$count = 0;
		foreach($news as $i=>$row) {
			if($row['id']==$news[$i-1]['id']) //if repeating news (because of tags)
				echo " <a href=\"./?tag=".$row['tagname']."\">#".$row['tagname']."</a>";
			else
			{
				$title = (strlen($row['title']) > 30) ? substr($row['title'],0,27).'...' : $row['title'];
				  echo "<div class=\"noticia_index\">
				  <h3><a href=\"./?id=".$row['id']."\">".stripslashes($title)."</a></h3>
				  <a href=\"./?id=".$row['id']."\"><img src=\"http://lorempixel.com/300/200/?dummy=".rand()."\" alt=\"300x200\"></a>
				  <div class=\"newsdetails\">
					<br />";
				echo displayDate($row['date']);
				if($row['tagname']!="")
				  echo "</div><div class=\"newstags\"><a href=\"./?tag=".stripslashes($row['tagname'])."\">#".stripslashes($row['tagname'])."</a>"; //first tag (close news details and start tags div)
			}
			if($row['id']!=$news[$i+1]['id']) { //if next row not a repeat, then close this news
				echo   "</div>";
			
				if(loggedIn() && (editor() || admin()))
				{
					if($count%4==0) //if leftmost
						echo "<ul class=\"left\">";
					elseif($count%4==3 || $i+1==sizeof($news)) //if at right (or last)
						echo "<ul class=\"right\">";
					else
						echo "<ul>";
					
					if(loggedIn() && (admin() || (editor() && ($_SESSION['username'] == $row['posted_by'] || $_SESSION['username'] == $row['imported_by']))))
						echo "<li><a href=\"editar_noticia.php?id=".$row['id']."\">Editar</a></li><li><a href=\"apagar_noticia.php?id=".$row['id']."\">Apagar</a></li>";
					echo "<li style=\"border:0;\"></li>"; //display full height <ul>
					echo "</ul>";
					$count++;
				}
				echo "</div>";
			}
		}
	}
	
	function showNewsId($news, $db)
	{
		foreach($news as $i=>$row) {
			if($i>0) //if repeating news (because of tags)
				echo " <a href=\"./?tag=".$row['tagname']."\">#".$row['tagname']."</a>";
			else
			{
				echo "<div class=\"noticia\" id=".$row['id'].">";
				if(loggedIn()) { //favorites
					if(hasFavorite($row['id'], $db))
						echo "<div class=\"del_favorite\" id=\"".$row['id']."\"><img width=\"30px\" src=\"common/star_filled.png\">";
					else
						echo "<div class=\"add_favorite\" id=\"".$row['id']."\"><img width=\"30px\" src=\"common/star_empty.png\">";
					echo "</div>";
				}
				echo "<h3>".stripslashes($row['title'])."</h3>
				<img src=\"http://lorempixel.com/300/200/\" alt=\"300x200\">				
				<div class=\"newsbody\">".nl2br/*convert newlines in database to <br>*/(stripslashes($row['text']))."</div>
				<div class=\"newsdetails\">
				<br />";
				if(!empty($row['url'])) //display URL if news is imported
					echo "<b>URL original:</b> <a target=\"_blank\" href=\"".stripslashes($row['url'])."\">".$row['url']."</a><br>";

				echo "<b>Submetida por:</b> ".getUserProfileLink($row['posted_by'], $db)."<br>";
				
				if(!empty($row['imported_by'])) //if news is imported
				{
					echo "<b>Importada por:</b> ".getUserProfileLink($row['imported_by'], $db)."<br>";
				}
				echo displayDate($row['date']);
				if($row['tagname']!="")
				  echo "</div><div class=\"newstags\"><a href=\"./?tag=".stripslashes($row['tagname'])."\">#".stripslashes($row['tagname'])."</a>"; //first tag (close news details and start tags div)

			}
			if(++$i == sizeof($news)) { //if next row is the end
				echo   "</div>";
							echo "<div class=comment".$row['id']."><h2>Comentários:</h2><div id=comments_server></div>";
							echo "</div>";
							if(isset($_SESSION['user_id']))
								echo "<div id=new_comment><textarea id=textarea_new_comment rows=4 placeholder=\"Novo Comentário...\"/></textarea><br><input id=send_comment type=button value=\"Enviar Comentário\"></div>";
				if(loggedIn() && (editor() || admin()))
				{
					echo "<ul>";
					
					echo "<li><a href=./>Ver Todas</a></li>";
					if(loggedIn() && (admin() || (editor() && ($_SESSION['username'] == $row['posted_by'] || $_SESSION['username'] == $row['imported_by']))))
						echo "<li><a href=\"editar_noticia.php?id=".$row['id']."\">Editar</a></li><li><a href=\"apagar_noticia.php?id=".$row['id']."\">Apagar</a></li>";
					echo "<li style=\"border:0;\"></li>"; //display full height <ul>
					echo "</ul>";
				}
				echo "</div>";
			}
		}
	}
	
	function showPagination($db, $p, $firstid, $lastid)
	{
			echo "<div id=controlos>";
			$totals = $db->query("select min(id) as first, max(id) as last from news")->fetch();
			if($p>0 && $firstid<$totals['last'])
				echo "<p style=\"float:left;margin:5px 0;\"><a href=\"./?p=".($p-1)."\"><</a></p>";
			if($lastid>$totals['first'])
				echo "<p style=\"float:right;margin:5px 0;\"><a href=\"./?p=".($p+1)."\">></a></p>";
			echo "</div>";
	}
	
	function showHeader($subtitle)
	{
		echo "<div id=\"cabecalho\">";
		echo "<a href=\"./\"><h1>Social News</h1></a>";
		if(!empty($subtitle))
			echo "<h2>".$subtitle."</h2>";
		echo "</div>";
	}
	
	function showFooter()
	{
		echo "<div id=\"rodape\">
				<p>Social News Project - Linguagens e Tecnologias Web @ FEUP - T5G7</p>
				<div class=copyright>2012 © Maria João Araújo | Vasco Gonçalves</div>
			</div>";
	}
	
	function showLoginMenu()
	{
		echo "<ul class=\"login\">";
		if(loggedIn())
			echo "<li id=username userid=".$_SESSION['user_id'].">Bem-vindo <a href=ver_perfil_utilizador.php?id=".$_SESSION['user_id'].">".$_SESSION['username']."</a></li><li><a href=\"logout.php\">Logout</a></li>";
		else
			echo "<li><a href=\"login.php\">Login</a></li>";
		echo "</ul>";
	}
	
	function showMessage()
	{
		if(isset($_SESSION['msg']))
		{
			echo "<div id=\"message\">".$_SESSION['msg']."<div id=\"close\">[ x ]</div></div>";
			unset($_SESSION['msg']);
		}
	}
	
	function getLatestNews($db)
	{
		if($stmt = $db->query('SELECT max(id) as max from news'))
		{
			$stmt = $stmt->fetch();
			return $stmt['max'];
		}
		else
			return "erro";
	}
?>

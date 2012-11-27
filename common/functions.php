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
	
	function loggedin() {
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
	
	function hasfavorite($news_id, $db) {
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
	
	function getuserprofilelink($username, $db) { // if the user exists get the link, else just echo the name
		$stmt = $db->prepare('SELECT id FROM user WHERE username = :username');
		$stmt->bindparam(':username', $username);
			
		if($stmt->execute())
		{
			$stmt = $stmt->fetchAll();
			
			if(count($stmt)==0) //if no results, the user profile can't be seen
				return $username;
			else
				return "<a href=ver_perfil_utilizador.php?id=".$stmt[0]['id'].">".$username."</a>";
		}
		return false;
	}
	
	function isnewsfromuser($news_id, $db)
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
	
	function displaydate($date)
	{
		if(date('dmY') == date('dmY', $date)) //if news is from today, display only time, otherwise display date and time
          echo "Hoje, ".date('H:i', $date);
        elseif(date('dmY', time()-86400) == date('dmY', $date)) //yesterday (1 day = 86400 seconds)
          echo "Ontem, ".date('H:i', $date);
        else
          echo date('d/m/Y, H:i', $date);
	}
	
	function showallnews($news)
	{
		foreach($news as $i=>$row) {
			if($row['id']==$news[$i-1]['id']) //if repeating news (because of tags)
				echo " <a href=\"./?tag=".$row['tagname']."\">#".$row['tagname']."</a>";
			else
			{
				$title = (strlen($row['title']) > 30) ? substr($row['title'],0,27).'...' : $row['title'];
				  echo "<div class=\"noticia_index\">
				  <h3><a href=\"?id=".$row['id']."\">".stripslashes($title)."</a></h3>
				  <a href=\"?id=".$row['id']."\"><img src=\"common/placeholder.jpg\" alt=\"300x200\" href=\"?id=".$row['id']."\"></a>
				  <div class=\"newsdetails\">
					<br />";
				displaydate($row['date']);
				if($row['tagname']!="")
				  echo "</div><div class=\"newstags\"><a href=\"./?tag=".stripslashes($row['tagname'])."\">#".stripslashes($row['tagname'])."</a>"; //first tag (close news details and start tags div)
			}
			if($row['id']!=$news[$i+1]['id']) { //if next row not a repeat, then close this news
				echo   "</div>";
			
				if(loggedin() && (editor() || admin()))
				{
					echo "<ul>";
					
					if(loggedin() && (admin() || (editor() && ($_SESSION['username'] == $row['posted_by'] || $_SESSION['username'] == $row['imported_by']))))
						echo "<li><a href=\"editar_noticia.php?id=".$row['id']."\">Editar</a></li><li><a href=\"apagar_noticia.php?id=".$row['id']."\">Apagar</a></li>";
					echo "<li style=\"border:0;\"></li>"; //display full height <ul>
					echo "</ul>";
				}
				echo "</div>";
			}
		}
	}
	
	function shownewsid($news, $db)
	{
		foreach($news as $i=>$row) {
			if($i>0) //if repeating news (because of tags)
				echo " <a href=\"./?tag=".$row['tagname']."\">#".$row['tagname']."</a>";
			else
			{
				echo "<div class=\"noticia\">";
				if(loggedin()) { //favorites
					if(hasfavorite($row['id'], $db))
						echo "<div class=\"del_favorite\" id=\"".$row['id']."\"><img width=\"30px\" src=\"common/star_filled.png\">";
					else
						echo "<div class=\"add_favorite\" id=\"".$row['id']."\"><img width=\"30px\" src=\"common/star_empty.png\">";
					echo "</div>";
				}
				echo "<h3>".stripslashes($row['title'])."</h3>
				<a href=\"common/placeholder.jpg\" target=_blank><img src=\"common/placeholder.jpg\" alt=\"300x200\"></a>
				<div class=\"newsbody\">".nl2br/*convert newlines in database to <br>*/(stripslashes($row['text']))."</div>
				<div class=\"newsdetails\">
				<br />";
				if(!empty($row['url'])) //display URL if news is imported
					echo "<b>URL original:</b> < <a href=\"".stripslashes($row['url'])."\">".$row['url']."</a><br>";

				echo "<b>Submetida por:</b> ".getuserprofilelink($row['posted_by'], $db)."<br>";
				
				if(!empty($row['imported_by'])) //if news is imported
				{
					echo "<b>Importada por:</b> ".getuserprofilelink($row['imported_by'], $db)."<br>";
				}
				displaydate($row['date']);
				if($row['tagname']!="")
				  echo "</div><div class=\"newstags\"><a href=\"./?tag=".stripslashes($row['tagname'])."\">#".stripslashes($row['tagname'])."</a>"; //first tag (close news details and start tags div)
			}
			if(++$i == sizeof($news)) { //if next row is the end
				echo   "</div>";
			
				if(loggedin() && (editor() || admin()))
				{
					echo "<ul>";
					
					echo "<li><a href=./>Ver Todas</a></li>";
					if(loggedin() && (admin() || (editor() && ($_SESSION['username'] == $row['posted_by'] || $_SESSION['username'] == $row['imported_by']))))
						echo "<li><a href=\"editar_noticia.php?id=".$row['id']."\">Editar</a></li><li><a href=\"apagar_noticia.php?id=".$row['id']."\">Apagar</a></li>";
					echo "<li style=\"border:0;\"></li>"; //display full height <ul>
					echo "</ul>";
				}
				echo "</div>";
			}
		}
	}
	
	function showpagination($db, $p, $firstid, $lastid)
	{
			echo "<div id=controlos>";
			$totals = $db->query("select min(id) as first, max(id) as last from news")->fetch();
			if($p>0 && $firstid<$totals['last'])
				echo "<p style=\"float:left;margin:5px 0;\"><a href=\"./?p=".($p-1)."\"><</a></p>";
			if($lastid>$totals['first'])
				echo "<p style=\"float:right;margin:5px 0;\"><a href=\"./?p=".($p+1)."\">></a></p>";
			echo "</div>";
	}
	
	function showheader($subtitle,$linkhome)
	{
		echo "<div id=\"cabecalho\">";
		if($linkhome)
			echo "<a href=\"./\"><h1>Social News</h1></a>";
		else
			echo "<h1>Social News</h1>";
		if(!empty($subtitle))
			echo "<h2>".$subtitle."</h2>";
		echo "</div>";
	}
	
	function showfooter()
	{
		echo "<div id=\"rodape\"> <!-- clear both needed because of pagination-->
				<p>Projecto 1 - Linguagens e Tecnologias Web @ FEUP - 2012</p>
			</div>";
	}
	
	function showloginmenu()
	{
		echo "<ul class=\"login\">";
		if(loggedin())
			echo "<li>Bem-vindo <a href=ver_perfil_utilizador.php?id=".$_SESSION['user_id'].">".$_SESSION['username']."</a></li><li><a href=\"logout.php\">Logout</a></li>";
		else
			echo "<li><a href=\"login.php\">Login</a></li>";
		echo "</ul>";
	}
?>
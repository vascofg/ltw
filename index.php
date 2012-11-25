<?php
	require_once 'common/functions.php';
	$id = (int)$_GET['id'];
	$tag = $_GET['tag'];
	$p = (int)$_GET['p'];
	$fav = (int)$_GET['fav'];
	if(!empty($fav) && !isset($_SESSION['username'])) //only allow favorite search if user is logged in
		redirectmsg("./", 'Operação não permitida');
	$offset = "offset ".$p*12;
 	if((!empty($id) && !empty($tag)) || (!empty($id) && !empty($fav)) || (!empty($tag) && !empty($fav))) //tag AND id AND favorite? no sir	
		redirect('./');
	if(isset($_SESSION['json_news']))
		unset($_SESSION['json_news']); //unset array of news from obter_noticias.php (if no news have been added)
	require_once 'db/news_query.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Social News</title>
		<link rel="stylesheet" href="common/style.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="common/favorites.js"></script>
	</head>
	<body>
		<div id="cabecalho">
<?php	if(!empty($id) || !empty($tag) || $p!=0 || !empty($fav)) //show link if not on all news listing
		echo "<a href=\"./\"><h1>Social News</h1></a>";
	else
		echo "<h1>Social News</h1>";?>
		</div>
		<div id="menu">
			<ul>
<?php
	if(isset($_SESSION['user_type']))
	{
		echo "<li><a href=\"./?fav=1\">Meus favoritos</a></li>";
		
		if($_SESSION['user_type']>0)
		{
			echo "<li><a href=\"nova_noticia.php\">Inserir notícia</a></li>";
		}

		if($_SESSION['user_type']==2)
		{
			echo "<li><a href=\"procurar_utilizador.php\">Gerir utilizadores</a></li>"
			."<li><a href=\"obter_noticias.php\">Obter notícias</a></li>";
		}
		
		echo "<li><a href=\"editar_perfil_utilizador.php?id=".$_SESSION['user_id']."\">Editar perfil</a></li>";
	}
	else
	{
		echo "<li></li>";
	}
?>
			</ul>
			<ul class="login"><?php
				if(isset($_SESSION['user_type']))
					echo "<li><a href=\"logout.php\">Logout</a></li>";
				else
					echo "<li><a href=\"login.php\">Login</a></li>";?>
			</ul>
		</div>
		<div id="conteudo">
			
<?php
	if(!empty($tag))
 		echo "<h4>#".$tag."</h4>";
	if(!empty($fav))
		echo "<h4>Favoritos</h4>";
	if(count($news)==0) //if no results
		echo "<h4>Nenhuma notícia encontrada</h4>";
	else {
	foreach($news as $i=>$row) {
      if($row['id']==$news[$i-1]['id']) //if repeating news (because of tags)
        echo " <a href=\"./?tag=".$row['tagname']."\">#".$row['tagname']."</a>";
      else
      {
      	$title = (strlen($row['title']) > 30) ? substr($row['title'],0,27).'...' : $row['title'];
        if(empty($id))
          echo "<div class=\"noticia_index\">
          <h3><a href=\"?id=".$row['id']."\">".stripslashes($title)."</a></h3>
          <a href=\"?id=".$row['id']."\"><img src=\"common/placeholder.jpg\" alt=\"300x200\" href=\"?id=".$row['id']."\"></a>
          <div class=\"newsdetails\">
            <br />";
        else
		{
          echo "<div class=\"noticia\">";
		  if(isset($_SESSION['username'])) {
			if(hasfavorite($id, $db))
				echo "<div class=\"del_favorite\" id=\"".$id."\"><img width=\"30px\" src=\"common/star_filled.png\">";
			else
				echo "<div class=\"add_favorite\" id=\"".$id."\"><img width=\"30px\" src=\"common/star_empty.png\">";
			echo "</div>";
		  }
		  echo "<h3>".stripslashes($row['title'])."</h3>
          <a href=\"common/placeholder.jpg\" target=_blank><img src=\"common/placeholder.jpg\" alt=\"300x200\"></a>
          <div style=\"clear:right;\" class=\"newsbody\">".nl2br/*convert newlines in database to <br>*/(stripslashes($row['text']))."</div>
          <div class=\"newsdetails\">
            <br />";
			if(!empty($row['url'])) //display URL if news is imported
				echo "URL original: <a href=\"".stripslashes($row['url'])."\">".$row['url']."</a><br>";
		
			// if the user exists in the database, its profile can be seen
			if(!empty($row['posted_by']))
			{
				$stmt = $db->prepare('SELECT id, user_type FROM user WHERE username = :username');
				$stmt->bindparam(':username', $row['posted_by']);
			}
	
			if($stmt->execute())
			{
				$stmt = $stmt->fetchAll();
				
				if(count($stmt)==0)
				{ //if no results, the user profile can't be seen
					 echo "Submetida por: ".$row['posted_by']."<br>";
				}
				else
				{
					 echo "Submetida por: <a href= ver_perfil_utilizador.php?posted_by=".$row['posted_by'].">".$row['posted_by']."</a><br>";
				}
			}	
				
          //only display text and details on detailed view (one news item)
		}
        if(date('dmY') == date('dmY', $row['date'])) //if news is from today, display only time, otherwise display date and time
          echo "Hoje, ".date('H:i', $row['date']);
        elseif(date('dmY', time()-86400) == date('dmY', $row['date'])) //yesterday (1 day = 86400 seconds)
          echo "Ontem, ".date('H:i', $row['date']);
        else
          echo date('d/m/Y, H:i', $row['date']);
        if($row['tagname']!="")
          echo "</div><div class=\"newstags\"><a href=\"./?tag=".stripslashes($row['tagname'])."\">#".stripslashes($row['tagname'])."</a>"; //first tag (close news details and start tags div)
      }
      if($row['id']!=$news[$i+1]['id']) { //if next row not a repeat, then close this new
        echo   "</div>";
		
		if(!empty($id) || (isset($_SESSION['username']) && $_SESSION['user_type']>0))
		{
			echo "<ul>";
			
			if(!empty($id))
				echo "<li><a href=./>Ver Todas</a></li>";
			if(isset($_SESSION['username']) && $_SESSION['user_type']>0)
				echo "<li><a href=\"editar_noticia.php?id=".$row['id']."\">Editar</a></li><li><a href=\"apagar_noticia.php?id=".$row['id']."\">Apagar</a></li>
					</ul>";
		}
		echo "</div>";
	}
}
		if(empty($tag) && empty($id) && empty($fav)) //pagination
		{	echo "<div id=controlos>";
			$totals = $db->query("select min(id) as first, max(id) as last from news")->fetch();
			if($p>0 && $news[0]['id']<$totals['last'])
				echo "<p style=\"float:left;margin:5px 0;\"><a href=\"./?p=".($p-1)."\"><</a></p>";
			$lastelem = end($news); //last element of array news
			if($lastelem['id']>$totals['first'])
				echo "<p style=\"float:right;margin:5px 0;\"><a href=\"./?p=".($p+1)."\">></a></p>";
			echo "</div>";
		}
	}?>
		</div>
		<div id="rodape" style="clear:both;"> <!-- clear both needed because of pagination-->
			<p>Projecto 1 - Linguagens e Tecnologias Web @ FEUP - 2012</p>
		</div>
<?php
		//display messages
		if(isset($_SESSION['msg']))
		{
			echo "<script type=\"text/javascript\">alert(\"".$_SESSION['msg']."\")</script>";
			unset($_SESSION['msg']);
		}
?>
	</body>
</html>

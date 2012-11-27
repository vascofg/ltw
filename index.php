<?php
	require_once 'common/functions.php';
	$id = (int)$_GET['id'];
	$tag = $_GET['tag'];
	$p = (int)$_GET['p'];
	$fav = (int)$_GET['fav'];
	if(!empty($fav) && !loggedin()) //only allow favorite search if user is logged in
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
<?php
	if(!empty($id) || !empty($tag) || $p!=0 || !empty($fav)) //show link if not on all news listing
	{
		if(!empty($tag)) //tag search
			showheader('#'.$tag, true);
		elseif(!empty($fav)) //favorites
			showheader('Favoritos', true);
		else
			showheader('', true);
	}
	else
		showheader('', false);
?>
		<div id="menu">
			<ul>
<?php
	if(loggedin())
	{
		echo "<li><a href=\"./?fav=1\">Meus favoritos</a></li>";
		
		if((editor() || admin()))
		{
			echo "<li><a href=\"nova_noticia.php\">Inserir notícia</a></li>";
		}

		if(admin())
		{
			echo "<li><a href=\"procurar_utilizador.php\">Gerir utilizadores</a></li>"
			."<li><a href=\"obter_noticias.php\">Obter notícias</a></li>";
		}
	}
	else
	{
		echo "<li></li>";
	}
?>
			</ul>
<?php
	showloginmenu()
?>
		</div>
		<div id="conteudo">
			
<?php
	if(count($news)==0) //if no results
		echo "<h5>Nenhuma notícia encontrada.</h5>";
	else {
		if(!empty($id)) //if news by id
			shownewsid($news,$db);
		else{ //in any other case
			showallnews($news);
			if(empty($tag) && empty($fav)) //only show pagination on all news listing
				showpagination($db, $p, $news[0]['id'], $news[sizeof($news)-1]['id']);
		}
	}
	echo "</div>";
	showfooter();
	//display messages
	if(isset($_SESSION['msg']))
	{
		echo "<script type=\"text/javascript\">alert(\"".$_SESSION['msg']."\")</script>";
		unset($_SESSION['msg']);
	}
?>
	</body>
</html>

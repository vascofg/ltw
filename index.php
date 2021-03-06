<?php
	require_once 'common/functions.php';
	$id = (int)$_GET['id'];
	$tag = $_GET['tag'];
	$p = (int)$_GET['p'];
	$fav = (int)$_GET['fav'];
	if(!empty($fav) && !loggedIn()) //only allow favorite search if user is logged in
		redirectMsg("./", 'Operação não permitida');
	$offset = $p*12;
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
		<script src="common/search.js"></script>
		<script src="common/messages.js"></script>
		<script src="common/comments.js"></script>
<?php
	if(!empty($id))
		echo "<script src=\"common/alertnews.js\"></script>";
?>
	</head>
	<body>
<?php
	
	if(!empty($tag)) //tag search
		showHeader('#'.$tag);
	elseif(!empty($fav)) //favorites
		showHeader('Favoritos');
	else
		showHeader('');
		
?>
		<div id="menu">
			<ul>
<?php
	if(loggedIn())
	{
		echo "<a href=\"./\"><img src=\"common/home.png\"></a><li><a href=\"./?fav=1\">Meus favoritos</a></li>";
		
		if((editor() || admin()))
		{
			echo "<li><a href=\"nova_noticia.php\">Inserir notícia</a></li>";
		}

		if(admin())
		{
			echo "<li><a href=\"procurar_utilizador.php\">Gerir utilizadores</a></li>".
			"<li><a href=\"gerir_servidor.php\">Gerir servidores</a></li>".
			"<li><a href=\"obter_noticias.php\">Obter notícias</a></li>";
		}
	}
	else
	{
		echo "<li></li>";
	}
	
	echo "</ul>";
	showLoginMenu();
	echo "</div>";
	showMessage();
	if(empty($tag) && empty($fav) && empty($id))
	{
		echo '<div id="search">
				<input type="text" name="search" size="75" placeholder="Introduza os termos de pesquisa..."> <input type="checkbox" name="title" checked="checked">Título <input type="checkbox" name="text">Texto <input type="checkbox" name="tag">Tag
			</div>';
	}
?>
		
		<div id="conteudo">
			
<?php
	if(count($news)==0) //if no results
		echo "<h5>Nenhuma notícia encontrada.</h5>";
	else {
		if(!empty($id)) //if news by id
			showNewsId($news,$db);
		else{ //in any other case
			showAllNews($news);
			if(empty($tag) && empty($fav)) //only show pagination on all news listing
				showPagination($db, $p, $news[0]['id'], $news[sizeof($news)-1]['id']);
		}
	}
	echo "</div>";
	showFooter();
?>
	</body>
</html>

<?php
	require_once 'common/functions.php';
	if(!empty($_POST['search']))
		$search = $_POST['search'];
	else
	{
		$p = (int)$_POST['p'];
		$offset = $p*12;
	}
	$title = $_POST['title'];
	$text = $_POST['text'];
	$searchtag = $_POST['searchtag'];

	require_once 'db/news_query.php';
	
	if(count($news)==0) //if no results
		echo "<h5>Nenhuma notícia encontrada.</h5>";
	else
		showallnews($news);
	if(!isset($search))
		showpagination($db, $p, $news[0]['id'], $news[sizeof($news)-1]['id']);
?>
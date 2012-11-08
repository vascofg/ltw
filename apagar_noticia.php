<?php
	include 'common/functions.php';
	
	$id=$_GET['id'];
	
	$db = new PDO('sqlite:db/news.db');
	$db->query('delete from news where rowid=' .$id);
	
	redirect("./");
?>
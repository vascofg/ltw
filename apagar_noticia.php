<?php
	session_start();
	require_once 'common/functions.php';
	if(!isset($_SESSION['username'])) //if not logged in, go away
		redirect("./");
	
	$id=$_GET['id'];
	
	$db = new PDO('sqlite:db/news.db');
	$db->query('delete from news where rowid=' .$id);
	
	redirect("./");
?>
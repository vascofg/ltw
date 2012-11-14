<?php
	session_start();
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<1 || !isset($_GET['id'])) //if not logged in, not editor or no id set, go away
		redirectmsg("./", 0);
	
	$id=$_GET['id'];
	
	if(!$db->query('delete from news where rowid=' .$id))
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
	else
		redirectmsg('./', 2);
?>
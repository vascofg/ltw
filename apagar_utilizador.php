<?php
	session_start();
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(!isset($_SESSION['username'])) //if not logged in, go away
		redirectmsg("./", 'Operação não permitida');
	
	$id=$_GET['id'];
	$stmt=$db->prepare('delete from user where rowid= :id');
	$stmt->bindparam(':id', $id);
	
	if(!$stmt->execute())
	{
		$error=$stmt->errorInfo();
		echo "Erro: " . $error[2];
	}
	else
		redirectmsg('./', 'Operação efectuada');
?>
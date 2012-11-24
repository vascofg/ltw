<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if((!isset($_SESSION['username'])) || (empty($_GET['id'])) || (((int)$_SESSION['user_type']) != 2 && ((int)$_SESSION['user_id']) != ((int)$_GET['id']))) //if not logged in, go away
		redirectmsg("./", 'Operação não permitida');
	
	$id=$_GET['id'];
	$stmt=$db->prepare('delete from user where rowid= :id');
	$stmt->bindparam(':id', $id);
	
	if(!$stmt->execute())
	{
		$error=$stmt->errorInfo();
		echo "Erro: " . $error[2];
	}
	elseif($id!=$_SESSION['user_id'])
		redirectmsg('./', 'Operação efectuada');
	else
		require_once('logout.php');
?>
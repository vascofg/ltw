<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(!loggedIn() || !admin() || empty($_GET['id']))
		redirectMsg("./", 'Operação não permitida');
	
	$id=$_GET['id'];
	$stmt=$db->prepare('delete from server where rowid= :id');
	$stmt->bindparam(':id', $id);
	
	if(!$stmt->execute())
	{
		$error=$stmt->errorInfo();
		echo "Erro: " . $error[2];
	}
	else
		redirectMsg('./gerir_servidor.php', 'Operação efectuada');
?>

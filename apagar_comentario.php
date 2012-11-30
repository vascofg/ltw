<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(!loggedin() || !isset($_GET['id'])) //if not logged in or no id set, go away
		die(json_encode('Operação não permitida1'));
	
	$id=(int)$_GET['id'];
	
	if(!admin() && !isCommentFromUser($id, $db)) //if user isn't admin and the comment isn't his, go away
		die(json_encode('Operação não permitida2'));
	
	$id=$_GET['id'];
	$stmt=$db->prepare('delete from comment where rowid= :id');
	$stmt->bindparam(':id', $id);
	
	if(!$stmt->execute())
	{
		$error=$db->errorInfo();
		echo json_encode("Erro: " . $error[2]);
	}
	else
		echo json_encode('ok');
?>
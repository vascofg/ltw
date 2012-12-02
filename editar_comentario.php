<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(!loggedIn() || !isset($_GET['id'])) //if not logged in or no id set, go away
		die(json_encode('Operação não permitida'));
	
	$id=(int)$_GET['id'];
	$text=$_GET['text'];
	$date = time();
	
	if(!isCommentFromUser($id, $db)) //if the comment isn't from the user who wants to edit it, go away
		die(json_encode('Operação não permitida'));
	
	$id=$_GET['id'];
	$stmt = $db->prepare('UPDATE comment SET text = :text, date = :date WHERE rowid = :id');
	$stmt->bindparam(':id', $id);
	$stmt->bindparam(':text', $text);
	$stmt->bindparam(':date', $date);
	
	if(!$stmt->execute())
	{
		$error=$db->errorInfo();
		echo json_encode("Erro: " . $error[2]);
	}
	else
		echo json_encode('ok');
?>
<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(!loggedIn() || !isset($_POST['id'])) //if not logged in or no id set, go away
		die(json_encode('Operação não permitida'));
	
	$id=(int)$_POST['id'];
	$text=$_POST['text'];
	$edition_date = time();
	$edited = 1; // if a comment is edited, edited == 1
	
	if(!isCommentFromUser($id, $db)) //if the comment isn't from the user who wants to edit it, go away
		die(json_encode('Operação não permitida'));
	
	$stmt = $db->prepare('UPDATE comment SET text = :text, edition_date = :edition_date, edited = :edited WHERE rowid = :id');
	$stmt->bindparam(':id', $id);
	$stmt->bindparam(':text', $text);
	$stmt->bindparam(':edition_date', $edition_date);
	$stmt->bindparam(':edited', $edited);
	
	if(!$stmt->execute())
	{
		$error=$db->errorInfo();
		echo json_encode("Erro: " . $error[2]);
	}
	else
		echo json_encode('ok');
?>
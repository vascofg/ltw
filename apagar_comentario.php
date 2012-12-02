<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(!loggedIn() || !isset($_GET['id'])) //if not logged in or no id set, go away
		die(json_encode('Operação não permitida'));
	
	$id=(int)$_GET['id'];
	$news_id=(int)$_GET['news_id'];
	
	if((user() && !isCommentFromUser($id, $db)) || ((editor() || admin()) && !isCommentFromUser($id, $db) && !isNewsFromUser($news_id, $db))) //if it's an user and the comment isn't his, or an editor/admin and the comment isn't in his news and it's not his comment, go away
		die(json_encode('Operação não permitida'));
	
	$stmt=$db->prepare('DELETE FROM comment WHERE rowid= :id');
	$stmt->bindparam(':id', $id);
	
	if(!$stmt->execute())
	{
		$error=$db->errorInfo();
		echo json_encode("Erro: " . $error[2]);
	}
	else
		echo json_encode('ok');
?>
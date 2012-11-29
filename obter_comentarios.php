<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';

	$id = $_GET['id'];
	
	$stmt = $db->prepare('select text, username, date from comment, user where user.id=comment.user_id and news_id = :id ');
	$stmt->bindparam(':id', $id);
	$stmt->execute();
	$comments = $stmt->fetchall();
	
	echo json_encode($comments);
?>
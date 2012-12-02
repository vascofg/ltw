<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';

	$id = $_GET['id'];
	
	$stmt = $db->prepare('SELECT comment.rowid, text, date,username FROM comment, user WHERE comment.rowid = :id and comment.user_id = user.id');
	$stmt->bindparam(':id', $id);
	$stmt->execute();
	$comments = $stmt->fetchall();
	$comment = $comments[0];
	
   	$comment['date_format']=displayDate($comment['date']);
   
   $ret = array();
   array_push($ret, $comment);
	
	echo json_encode($ret);
?>
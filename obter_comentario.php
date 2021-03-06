<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';

	$id = $_GET['id'];
	
	$stmt = $db->prepare('SELECT comment.rowid, text, date, edition_date, edited, username FROM comment, user WHERE comment.rowid = :id and comment.user_id = user.id');
	$stmt->bindparam(':id', $id);
	$stmt->execute();
	$comments = $stmt->fetchall();
	$comment = $comments[0];
	
   	$comment['date_format']=displayDate($comment['date']);
   	$comment['edition_date_format']=displayDate($comment['edition_date']);
	$comment['text']=nl2br(stripslashes($comment['text']));
   
   $ret = array();
   array_push($ret, $comment);
	
	echo json_encode($ret);
?>
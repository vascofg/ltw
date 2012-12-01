<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';

	$id = $_GET['id'];
	
	$stmt = $db->prepare('select comment.rowid, text, date from comment where rowid = :id');
	$stmt->bindparam(':id', $id);
	$stmt->execute();
	$comments = $stmt->fetchall();
	$comment = $comments[0];
	
   	$comment['date_format']=displaydate($comment['date']);
   
   $ret = array();
   array_push($ret, $comment);
	
	echo json_encode($ret);
?>
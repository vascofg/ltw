<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';

	$id = $_GET['id'];
	
	$stmt = $db->prepare('select comment.rowid, text, username, date from comment, user where user.id=comment.user_id and news_id = :id ');
	$stmt->bindparam(':id', $id);
	$stmt->execute();
	$comments = $stmt->fetchall();
	
	$ret = array();
	
	foreach ($comments as $key => $value)
   	{
   	 $value['date_format']=returnDate($value['date']);
   	 $value['deletable']=isCommentFromUser($value['rowid'], $db);
   	 $value['editable']=isCommentFromUser($value['rowid'], $db);
   	 array_push($ret, $value);
   	}
   	
	
	echo json_encode($ret);
?>
<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';

	$id = $_GET['id'];
	
	$stmt = $db->prepare('select comment.rowid, text, username, date from comment, user where user.id=comment.user_id and news_id = :id order by comment.rowid desc');
	$stmt->bindparam(':id', $id);
	$stmt->execute();
	$comments = $stmt->fetchall();
	
	$ret = array();
	
	foreach ($comments as $key => $value)
   	{
   	 $value['date_format']=displayDate($value['date']);
   	 $value['editable']=isCommentFromUser($value['rowid'], $db);
   	 
   	 if(editor() || admin())
   	 {
   	 	$value['deletable']=(isNewsFromUser($id, $db) || isCommentFromUser($value['rowid'], $db));
   	 }
   	 else
   	 {
   	 	$value['deletable']=isCommentFromUser($value['rowid'], $db);
   	 }
   	 
   	 array_push($ret, $value);
   	}
	
	echo json_encode($ret);
?>
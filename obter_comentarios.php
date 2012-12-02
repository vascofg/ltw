<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';

	$id = $_GET['id'];
	
	$stmt = $db->prepare('SELECT comment.rowid, text, username, date, edition_date, edited FROM comment, user WHERE user.id=comment.user_id AND news_id = :id ORDER BY comment.rowid DESC');
	$stmt->bindparam(':id', $id);
	$stmt->execute();
	$comments = $stmt->fetchall();
	
	$ret = array();
	
	foreach ($comments as $key => $value)
   	{
   	 $value['date_format']=displayDate($value['date']);
   	 $value['edition_date_format']=displayDate($value['edition_date']);
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
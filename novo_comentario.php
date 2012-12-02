<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';

	$news_id =(int) $_GET['news_id'];
	$user_id = (int) $_SESSION['user_id'];
	$text = $_GET['text'];
	$date = time();
	$edition_date = time();
	$edited = 0; // if a comment is new, edited == 0
	
	$stmt = $db->prepare('INSERT INTO comment (news_id,user_id,text,date,edition_date,edited) VALUES (:news_id, :user_id, :text, :date, :edition_date, :edited)');
	$stmt->bindparam(':news_id', $news_id);
	$stmt->bindparam(':user_id', $user_id);
	$stmt->bindparam(':text', $text);
	$stmt->bindparam(':date', $date);
	$stmt->bindparam(':edition_date', $edition_date);
	$stmt->bindparam(':edited', $edited);
	
	if($stmt->execute())
	{
		echo json_encode('ok');
	}
	else
		echo json_encode($stmt->errorInfo());	
?>
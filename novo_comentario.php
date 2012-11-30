<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';

	$news_id =(int) $_GET['news_id'];
	$user_id = (int) $_SESSION['user_id'];
	$text = $_GET['text'];
	$date = time();
	//var_dump($news_id);
	$stmt = $db->prepare('insert into comment (news_id,user_id,text,date) values (:news_id, :user_id, :text, :date)');
	$stmt->bindparam(':news_id', $news_id);
	$stmt->bindparam(':user_id', $user_id);
	$stmt->bindparam(':text', $text);
	$stmt->bindparam(':date', $date);
	
	if($stmt->execute())
		echo json_encode('ok');
	else
		echo json_encode($stmt->errorInfo());	
?>
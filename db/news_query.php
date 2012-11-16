<?php
	require_once 'db/db.php';
	if(isset($id) && !empty($id))
	{
		$stmt = $db->query('SELECT id, title, date, text, posted_by, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id where id='.$id);
	}
	elseif(isset($tag) && !empty($tag))
	{
			$stmt = $db->query('SELECT id, title, date FROM news LEFT JOIN tag ON news.id=tag.news_id where tagname=\''.$tag.'\'');
	}
	else
	{
		$stmt = $db->query('SELECT id, title, date, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id ORDER BY id DESC LIMIT 10');
	}
	if(!stmt)
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
	else
		$news = $stmt->fetchAll();
?>

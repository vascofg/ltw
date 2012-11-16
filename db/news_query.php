<?php
	require_once 'db/db.php';
	if(isset($id) && !empty($id))
	{
		$stmt = $db->query('SELECT id, title, date, text, posted_by, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id where id='.$id);
	}
	elseif(isset($tag) && !empty($tag))
	{
			$stmt = $db->query('SELECT id, title, date FROM news LEFT JOIN tag ON news.id=tag.news_id where tagname=\''.$tag.'\' ORDER BY id DESC');
	}
	else
	{
		$stmt = $db->query('SELECT id, title, date, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id  where news.id in (select id from news order by id desc limit 10) ORDER BY id DESC');
	}
	if(!stmt)
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
	else
		$news = $stmt->fetchAll();
?>

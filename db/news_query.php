<?php
	require_once 'db/db.php';
	if(isset($id) && !empty($id))
		$sql = 'SELECT id, title, date, text, posted_by, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id where id='.$id;
	elseif(isset($tag) && !empty($tag))
		$sql = 'SELECT id, title, date, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id where news.id in (select news_id from tag where tagname=\''.$tag.'\') ORDER BY id DESC';
	else
		$sql = 'SELECT id, title, date, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id where news.id in (select id from news order by id desc limit 10 '.$offset.') ORDER BY id DESC';
	$stmt = $db->query($sql);
	if(!stmt)
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
	else
		$news = $stmt->fetchAll();
?>

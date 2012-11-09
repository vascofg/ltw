<?php
	$db = new PDO('sqlite:db/news.db');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	if(isset($id) && !empty($id))
	{
		$stmt = $db->query('SELECT rowid as id, * FROM news where rowid='.$id);
		if(!$stmt)
			$stmt = array("error" => array("id" => "NO_SUCH_NEWS", "text" => "No such news"));
	}
	else
	{
		$stmt = $db->query('SELECT rowid as id, * FROM news');
	}
	$news = $stmt->fetchAll();
?>

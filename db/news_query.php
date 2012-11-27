<?php
	require_once 'db/db.php';
	if(isset($id) && !empty($id))
	{
		$stmt = $db->prepare('SELECT id, title, date, text, posted_by, imported_by, url, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id where id=:id');
		$stmt->bindparam(':id', $id);
	}
	elseif(isset($tag) && !empty($tag))
	{
		$stmt = $db->prepare('SELECT id, title, date, posted_by, imported_by, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id where news.id in (select news_id from tag where tagname=:tag) ORDER BY id DESC');
		$stmt->bindparam(':tag', $tag);
	}
	elseif(isset($fav) && !empty($fav))
	{
		$stmt = $db->prepare('SELECT id, title, date, posted_by, imported_by, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id LEFT JOIN favorite ON news.id=favorite.news_id where favorite.user_id = :user_id ORDER BY id DESC');
		$stmt->bindparam(':user_id', $_SESSION['user_id']);
	}
	elseif(isset($search) && !empty($search))
	{
		$search = "%".$search."%"; //add %
		$stmt = $db->prepare('SELECT id, title, date, posted_by, imported_by, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id where title like :titleparam or text like :textparam or news.id in (select news_id from tag where tagname like :tagparam) ORDER BY id DESC');
		if($title)
			$stmt->bindparam(':titleparam', $search);
		if($text)
			$stmt->bindparam(':textparam', $search);
		if($searchtag)
			$stmt->bindparam(':tagparam', $search);
	}
	else
	{
		$stmt = $db->prepare('SELECT id, title, date, posted_by, imported_by, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id where news.id in (select id from news order by id desc limit 12 offset :offset) ORDER BY id DESC');
		$stmt->bindparam(':offset', $offset);
	}
	if(!$stmt->execute())
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
	else
		$news = $stmt->fetchAll();
?>

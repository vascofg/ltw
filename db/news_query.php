<?php
	require_once 'db/db.php';
	if(isset($id) && !empty($id))
	{
		$stmt = $db->query('SELECT rowid as id, * FROM news where rowid='.$id);
		if(!$stmt)
			$stmt = array("error" => array("id" => "NO_SUCH_NEWS", "text" => "No such news"));
	}
	else
	{
		$stmt = $db->query('SELECT rowid as id, * FROM news ORDER BY rowid DESC');
	}
	$news = $stmt->fetchAll();
?>

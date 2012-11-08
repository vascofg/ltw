<?php
	$db = new PDO('sqlite:db/news.db');
	$id = $_GET['id'];
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	if(isset($id) && !empty($id))
	{
		$result = $db->query('SELECT rowid as id, * FROM news where rowid=' .$id);
		if(!$result)
			$result = array("error" => array("id" => "NO_SUCH_NEWS", "text" => "No such news"));
	}
	else
	{
		$result = $db->query('SELECT rowid as id, * FROM news');
	}
	$result = $result->fetchAll();
	
	echo json_encode($result);
?>

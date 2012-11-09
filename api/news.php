<?php
	$db = new PDO('sqlite:../db/news.db');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	/*$tags = $_GET['tags'];
	$tags = str_replace(' ', ' or ', $tags);
	echo $tags;*/
	$start_date = $_GET['start_date'];
	$end_date = $_GET['end_date'];
	//convert to timestamp
	$start_date = strtotime($start_date);
	$end_date = strtotime($end_date);

	//if only end_date is specified then show all news until the end time
	if(!isset($start_date) || empty($start_date))
		$start_date=0;
	//if only start_date is specified then show news from start to the current time
	if(!isset($end_date) || empty($end_date))
		$end_date=time();
	//if none specified then it will show from 0 to time() which means all news in the database

	$stmt = $db->query('SELECT rowid, * FROM news where date > '.$start_date.' and date < '.$end_date);
	if($stmt)
	{
		foreach($stmt as $i=>$row)
		{
			$data[$i] = array("id" => $row['rowid'], "title" => $row['title'], "date" => date('c', $row['date']),
				"text" => $row['text'], "posted_by" => $row['posted_by'], "url" => $row['url']);
		}
		$result = array ("result" => "success", "server_name" => "Grupo X", "data" => $data);
	}
	else
	{
		$error=$db->errorInfo();
		$result = array ("result" => "error", "reason" => $error[2], "code" => $error[0]);
	}
	$json = json_encode($result);
	echo $json;
?>

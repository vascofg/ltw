<?php
	require_once '../db/db.php';
	$tags = $_GET['tags'];
	$tags = preg_replace('/\s*(\w+)/', '\'${1}\'', $tags); //encase in ''
	$tags = str_replace('\'\'', '\',\'', $tags); //replace spaces with commas
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

	if(!empty($tags))
		$stmt = $db->query('SELECT * FROM news LEFT JOIN tag ON news.id=tag.news_id  where news.id in (select news_id from tag where tagname in ('.$tags.')) and date > '.$start_date.' and date < '.$end_date.' ORDER BY id DESC');
	else
		$stmt = $db->query('SELECT * FROM news LEFT JOIN tag ON news.id=tag.news_id where date > '.$start_date.' and date < '.$end_date.' ORDER BY id DESC');
	if($stmt)
	{
		$stmt = $stmt->fetchAll();
		$tags = array();
		$data_array_pos=0; //data array position
		foreach($stmt as $i=>$row)
		{
			if($row['id']==$stmt[$i-1]['id']) //if repeating
				array_push($tags, $row['tagname']);
			else
			{
				$data[$data_array_pos] = array("id" => $row['id'], "title" => $row['title'], "date" => date('c', $row['date']),
				"text" => $row['text'], "posted_by" => $row['posted_by'], "url" => 'http://'.$_SERVER["SERVER_NAME"].dirname(dirname($_SERVER["REQUEST_URI"])).'/?id='.$row['id']);
				if($row['tagname']!="")
					array_push($tags, $row['tagname']);
			}
				
			if($row['id']!=$stmt[$i+1]['id']) { //if next row not a repeat, close array of tags
				$data[$data_array_pos]['tags']=$tags; //append tags to array data on corresponding news
				$tags=array(); //empty tag array
				$data_array_pos++; //next data array position
			}
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

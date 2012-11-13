<?php
	$db = new PDO('sqlite:'.dirname(__FILE__).'/news.db'); //get full path so it works with all subfolders
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
?>
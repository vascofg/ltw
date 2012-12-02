<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(loggedIn()){
	
		$id=(int)$_POST['id'];
		$op=(int)$_POST['op']; //1 is remove
		if($op==1)
			$stmt = $db->prepare('delete from favorite where news_id = ? and user_id = ?');
		else
			$stmt = $db->prepare('insert into favorite values (?,?)');
		if($stmt->execute(array($id,$_SESSION['user_id'])))
			echo "Sucesso";
		else
		{
			$error=$db->errorInfo();
			echo "Erro: " . $error[2];
		}
	}
?>
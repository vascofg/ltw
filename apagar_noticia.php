<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<1 || !isset($_GET['id'])) //if not logged in, not editor or no id set, go away
		redirectmsg("./", 'Operação não permitida');
	
	$id=(int)$_GET['id'];
	
	if($_SESSION['user_type']<2 && !isnewsfromuser($id, $db)) //if user isn't admin and news isn't his, go away
		redirectmsg("./", 'Operação não permitida');
	
	if(!$db->query('delete from news where id=' .$id))
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
	else
		redirectmsg('./', 'Operação efectuada');
?>
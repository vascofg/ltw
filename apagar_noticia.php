<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(!loggedIn() || user() || !isset($_GET['id'])) //if not logged in, not editor or no id set, go away
		redirectMsg("./", 'Operação não permitida');
	
	$id=(int)$_GET['id'];
	
	if(!admin() && !isNewsFromUser($id, $db)) //if user isn't admin and news isn't his, go away
		redirectMsg("./", 'Operação não permitida');
	
	if(!$db->query('delete from news where id=' .$id))
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
	else
		redirectMsg('./', 'Operação efectuada');
?>
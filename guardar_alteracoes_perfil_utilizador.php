<?php
	require_once 'common/functions.php';
	require_once 'db/db.php'; //in this file it's needed either way
	if(!loggedIn())
		redirectMsg("./", 'Operação não permitida');
	$pass_actual=$_POST['pass_actual'];
	if(!loggedIn() || (!isset($pass_actual) && !admin())) //if not logged in or not admin, go away
		redirectMsg("./", 'Operação não permitida');
	$username=$_GET['username'];
	$user_type=$_POST['user_type'];
		
	$nova_pass=$_POST['nova_pass'];
	$nova_pass_2=$_POST['nova_pass_2'];
	
	// if the user isn't an administrator, his current password needs to be verified
	if(!admin())
	{
		$password=crypt($username.$pass_actual, '$1$'.substr(md5($pass_actual.$username), 0, 8)); //le awesome salt
		$stmt = $db->prepare('SELECT id, username, user_type FROM user WHERE username = :username and password=:password');
		$stmt->bindparam(':username', $username);
		$stmt->bindparam(':password', $password);
	}
	// if the user is an administrator, he doesn't need to input the current password of the user
	else
	{
		$stmt = $db->prepare('SELECT id, username, user_type FROM user WHERE username = :username ');
		$stmt->bindparam(':username', $username);
	}
	
	$stmt->execute();
	$stmt = $stmt->fetchAll();
	$noResults = false;
	$differentPasswords = false;
	
	if(count($stmt)==0){ //if no results, password is wrong
		redirectmsg('editar_perfil_utilizador.php?id='.$_SESSION['user_id'],'A password actual não está correcta');
		
	}
	else {
		
		$row=$stmt[0];
		
		if($nova_pass != $nova_pass_2)
			redirectmsg('editar_perfil_utilizador.php?id='.$row['id'],'As passwords inseridas são diferentes');
			
		if(preg_match('/\W/',$username) || preg_match('/\W/',$nova_pass) || preg_match('/\W/',$nova_pass_2))
			redirectMsg('editar_perfil_utilizador.php?id='.$row['id'],'Apenas caracteres alfanuméricos');
		else
		{	
			if(strlen($nova_pass)>0) {
				$password=crypt($username.$nova_pass, '$1$'.substr(md5($nova_pass.$username), 0, 8)); //le awesome salt
				$stmt_password = $db->prepare('UPDATE user SET password =:nova_pass WHERE username = :username');
				$stmt_password->bindparam(':nova_pass', $password);
				$stmt_password->bindparam(':username', $username);
				$stmt_password->execute();
			}
			
			if(isset($user_type)) {
				$stmt_user_type = $db->prepare('UPDATE user SET user_type = :user_type WHERE username = :username');
				$stmt_user_type->bindparam(':user_type', $user_type);
				$stmt_user_type->bindparam(':username', $username);
				$stmt_user_type->execute();
			}
			
			redirectmsg('ver_perfil_utilizador.php?id='.$row['id'],'Operação efectuada');
		}	
	}
?>
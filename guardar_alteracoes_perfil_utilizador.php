<?php
	require_once 'common/functions.php';
	require_once 'db/db.php'; //in this file it's needed either way
	$pass_actual=$_POST['pass_actual'];
	if(!loggedin() || (!isset($pass_actual)&&!admin())) //if not logged in or not admin, go away
		redirectmsg("./", 'Operação não permitida');
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
	
	if(count($stmt)==0){ //if no results
		echo "<h5>Os dados não foram alterados!</h5>";
		echo "<h5>Nenhum utilizador encontrado.</h5>";
	}
	else {
		
		$row=$stmt[0];
		
		if($nova_pass != $nova_pass_2)
		{
			echo "<h5>Os dados não foram alterados!</h5>";
			
			echo "<h5>A nova password não foi bem confirmada!</h5>";
		}	
		else
		{	
			if(strlen($nova_pass)>0){
				$password=crypt($username.$nova_pass, '$1$'.substr(md5($nova_pass.$username), 0, 8)); //le awesome salt
				$stmt_password = $db->prepare('UPDATE user SET password =:nova_pass WHERE username = :username');
				$stmt_password->bindparam(':nova_pass', $password);
				$stmt_password->bindparam(':username', $username);
			 }
			if(isset($user_type)){
				$stmt_user_type = $db->prepare('UPDATE user SET user_type = :user_type WHERE username = :username');
				$stmt_user_type->bindparam(':user_type', $user_type);
				$stmt_user_type->bindparam(':username', $username);
			}
		}	
		
	}
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Editar Perfil do Utilizador</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
<?php
	showheader('Editar Perfil do Utilizador');
?>
		<div id="menu">
			<ul>
				<a href="./"><img src="common/home.png"></a><li>Apagar Utilizador</li>
			</ul>
<?php
	showloginmenu()
?>
		</div>
		<div id="conteudo">
		<?php
		$executa_password=(!isset($stmt_password) || $stmt_password->execute());
		$executa_user_type=(!isset($stmt_user_type) || $stmt_user_type->execute());
		
		if($executa_password && $executa_user_type)
		{
			echo "<h5>Perfil editado com sucesso!</h5>";
		}
		else
		{
			echo "<h5>A edição de dados falhou!</h5>";
		}
		
		echo "</div>";
		showfooter();
?>
	</body>
</html>

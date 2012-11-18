<?php
	session_start();
	require_once 'common/functions.php';
	require_once 'db/db.php'; //in this file it's needed either way
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<2) //if not logged in or not admin, go away
		redirectmsg("./", 0);
	$username=$_GET['username'];
	$user_type=$_POST['user_type'];
	$pass_actual=$_POST['pass_actual'];
		
	$nova_pass=$_POST['nova_pass'];
	$nova_pass_2=$_POST['nova_pass_2'];
	
	// if the user isn't an administrator, his current password needs to be verified
	if($_SESSION['user_type'] != 2)
	{
	
	$password=crypt($username.$pass_actual, '$1$'.substr(md5($pass_actual.$username), 0, 8)); //le awesome salt
	$stmt = $db->prepare('SELECT rowid, username, user_type FROM user WHERE username = :username and password=:password');
	$stmt->bindparam(':username', $username);
	$stmt->bindparam(':password', $password);
	}
	// if the user is an administrator, he doesn't need to input the current password of the user
	else
	{
	$stmt = $db->prepare('SELECT rowid, username, user_type FROM user WHERE username = :username ');
	$stmt->bindparam(':username', $username);
	}
	
	$stmt->execute();
	$stmt = $stmt->fetchAll();
	
	if(count($stmt)==0){ //if no results
		echo "<p>Os dados não foram alterados.</p>";
		echo "<p>Nenhum utilizador encontrado</p>";
	}
	else {
		
		$row=$stmt[0];
		
		if($nova_pass != $nova_pass_2)
		{
			echo "<p>Os dados não foram alterados.</p>";
			
			echo "<p>A nova password não foi bem confirmada!</p>";
		}	
		else
		{
			$password=crypt($username.$nova_pass, '$1$'.substr(md5($nova_pass.$username), 0, 8)); //le awesome salt
			$stmt = $db->prepare('UPDATE user SET user_type = :user_type, password =:nova_pass WHERE username = :username');
			$stmt->bindparam(':user_type', $user_type);
			
			if(empty($nova_pass) || empty($nova_pass_2))
			{
				$stmt->bindparam(':nova_pass', $pass_actual);
			}
			else			
			{
				$stmt->bindparam(':nova_pass', $password);
			}
			
			$stmt->bindparam(':username', $username);
			
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
		<div id="cabecalho">
			<a href="./"><h1>Social News</h1></a>
			<h2>Editar Perfil do Utilizador</h2>
		</div>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li><li>Apagar Utilizador</li>
			</ul>
			<ul class="login">
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div id="conteudo">
		<?php
		
		if(! $stmt->execute())
			{
				echo "<p>A edição de dados falhou!</p>";
			}
			else
			{
				echo "<p>Perfil editado com sucesso!</p>";
			}
		
		?>
		
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
	</body>
</html>

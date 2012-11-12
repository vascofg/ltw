<?php
	session_start();
	require_once 'common/functions.php';
	if(isset($_SESSION['username'])) //if logged in, go away
		redirectmsg("./", 0);
	if($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Login</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
			<h1>Login</h1>
			<h2>Autentique-se</h2>
		</div>
		<div id="conteudo">
			<form method="post">
				<table style="margin: auto;">
					<tr>
						<td>Username</td>
						<td><input type="text" name="username"></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type="password" name="password"></td>
					</tr>
				</table>
				<p style="text-align:center;"><input type="checkbox" name="register">Registar</p>
				<p style="text-align:center;"><input type="button" name="back" value="Voltar" onClick="javascript:location.href = './';">
				<input type="submit" value="Submeter"></p>
			</form>
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
<?
		//display messages
		if(isset($_GET['msgid']))
		{
			$msgid=$_GET['msgid'];
			echo "<script type=\"text/javascript\">";
			switch($msgid)
			{
				case 0:	echo "alert(\"Utilizador registado\");";
						break;
				case 1: echo "alert(\"Dados de login errados\");";
						break;
			}
			echo "</script>"; 
		}
?>
	</body>
</html>

<?
	}
	else
	{
		$username=$_POST['username'];
		$password=$_POST['password'];
		$password=crypt($username.$password, '$1$'.substr(md5($password.$username), 0, 8)); //le awesome salt
		
		$db = new PDO('sqlite:db/news.db');
		if(isset($_POST['register']))
		{
			$stmt = $db->prepare('INSERT INTO user values(?, ?, 0)');
			$stmt = $stmt->execute(array($username, $password));
			if($stmt)
				redirectmsg($_SERVER['PHP_SELF'], 0);
		}
		else
		{
			$stmt = $db->query('SELECT count(*) as count, user_type FROM user where username="'.$username.'" and password="'.$password.'"');
			if($stmt)
			{
				$result = $stmt -> fetch();
				if($result['count']>0)
				{
					// Register session data
					$_SESSION['username'] = $result['username'];
					$_SESSION['user_type'] = $result['user_type'];
					redirectmsg("./", 1);
				}
				else
					redirectmsg($_SERVER['PHP_SELF'], 1);
			}
		}
		if(!$stmt)
		{
			$error=$db->errorInfo();
			echo "Erro: " . $error[2];
		}
	}
?>

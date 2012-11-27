<?php
	require_once 'common/functions.php';
	if(loggedin()) //if logged in, go away
		redirectmsg("./", 'Operação não permitida');
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
<?php
	showheader('Login', true);
?>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li>
			</ul>
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
				<p style="text-align:center;"><input type="submit" value="Submeter"></p>
			</form>
		</div>
<?php
		showfooter();
		//display messages
		if(isset($_SESSION['msg']))
		{
			echo "<script type=\"text/javascript\">alert(\"".$_SESSION['msg']."\")</script>";	
	        unset($_SESSION['msg']);
		}
?>
	</body>
</html>

<?php
	}
	else
	{
		$username=$_POST['username'];
		$password=$_POST['password'];
		$password=crypt($username.$password, '$1$'.substr(md5($password.$username), 0, 8)); //le awesome salt
		
		require_once 'db/db.php';
		if(isset($_POST['register']))
		{
			$stmt = $db->prepare('INSERT INTO user values(null, ?, ?, 0)');
			$stmt = $stmt->execute(array($username, $password));
			if($stmt)
			{
				// Register session data
				$_SESSION['username'] = $username;
				$_SESSION['user_type'] = 0;
				$_SESSION['user_id'] = $db->lastInsertID();
				redirectmsg('./', 'Utilizador registado\nLogin efectuado');
			}
		}
		else
		{
			$stmt = $db->query('SELECT id, count(*) as count, username, user_type FROM user where username="'.$username.'" and password="'.$password.'"');
			if($stmt)
			{
				$result = $stmt -> fetch();
				if($result['count']>0)
				{
					// Register session data
					$_SESSION['username'] = $result['username'];
					$_SESSION['user_type'] = $result['user_type'];
					$_SESSION['user_id'] = $result['id'];
					//redirectmsg("./", 1); annoying
					redirect("./");
				}
				else
					redirectmsg($_SERVER['PHP_SELF'], 'Dados de login errados');
			}
		}
		if(!$stmt)
		{
			$error=$db->errorInfo();
			if($error[1]==19)
				redirectmsg($_SERVER['PHP_SELF'], 'Utilizador já registado');
			echo "Erro: " . $error[2];
		}
	}
?>

<?php
	require_once 'common/functions.php';
	if(loggedIn()) //if logged in, go away
		redirectMsg("./", 'Operação não permitida');
	if($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Login</title>
		<link rel="stylesheet" href="common/style.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="common/messages.js"></script>
	</head>
	<body>
<?php
	showHeader('Login');
?>
		<div id="menu">
			<ul>
				<a href="./"><img src="common/home.png"></a>
			</ul>
		</div>
<?php
	showMessage();
?>
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
		showFooter();
?>
	</body>
</html>

<?php
	}
	else
	{
		$username=$_POST['username'];
		$password=$_POST['password'];
		
		if(preg_match('/\W/',$username) || preg_match('/\W/',$password))
			redirectMsg($_SERVER['PHP_SELF'], 'Apenas caracteres alfanuméricos');
		
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
				redirectMsg('./', 'Utilizador registado e login efectuado');
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
					redirectMsg($_SERVER['PHP_SELF'], 'Dados de login errados');
			}
		}
		if(!$stmt)
		{
			$error=$db->errorInfo();
			if($error[1]==19)
				redirectMsg($_SERVER['PHP_SELF'], 'Utilizador já registado');
			echo "Erro: " . $error[2];
		}
	}
?>

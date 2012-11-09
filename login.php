<?php
	session_start();
	require_once 'common/functions.php';
	if(isset($_SESSION['username'])) //if logged in, go away
		redirect("./");
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
					<tr>
						<td><input type="checkbox" name="register">Registar</td>
						<td>
							<button name="back">Voltar</button>
							<input type="submit" value="Submeter">
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
	</body>
</html>

<?php
	require_once 'common/functions.php';
	if(isset($_POST['back']))
		redirect("./");
	if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password']))
	{
		$username=$_POST['username'];
		$password=$_POST['password'];
		$password=crypt($username.$password, '$1$'.substr(md5($password.$username), 0, 8)); //le awesome salt
		
		$db = new PDO('sqlite:db/news.db');
		if(isset($_POST['register']))
		{
			$stmt = $db->prepare('INSERT INTO users values(?, ?)');
			$stmt = $stmt->execute(array($username, $password));
			if($stmt)
				echo "User registered";
		}
		else
		{
			$stmt = $db->query('SELECT count(*) as count FROM users where username="'.$username.'" and password="'.$password.'"');
			if($stmt)
			{
				$result = $stmt -> fetch();
				if($result['count']>0)
				{
					// Register session data
					$_SESSION['username'] = $username;
					redirect("./");
				}
				else
					echo "No user found!";
			}
		}
		if(!$stmt)
		{
			$error=$db->errorInfo();
			echo "Erro: " . $error[2];
		}
	}
?>

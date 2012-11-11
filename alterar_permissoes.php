<?php
	session_start();
	require_once 'common/functions.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<2) //if not logged in or not admin, go away
		redirect("./");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Alterar permissões</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
			<h1>Alterar permissões</h1>
			<h2>Mudar níveis de permissão de utilizadores</h2>
		</div>
		<div id="conteudo">
			<form method="post">
				<table style="margin: auto;" border="1">
					<tr>
						<td>Username</td>
						<td>Tipo de Utilizador</td>
					</tr>
<?
	$db = new PDO('sqlite:db/news.db');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	
	$stmt = $db->query('SELECT rowid, username, user_type FROM user');
	if($stmt)
	{
		foreach($stmt as $row)
		{
			echo "
				<tr>
					<td>
						".$row['username']."
					</td>
					<td>
						<select name = user_type[".$row['rowid']."]>
							<option value=0";
			if($row['user_type']==0)
				echo " selected=\"selected\"";
			echo ">Utilizador</option>
			<option value=1";
			if($row['user_type']==1)
				echo " selected=\"selected\"";
			echo ">Editor</option>
			<option value=2";
			if($row['user_type']==2)
				echo " selected=\"selected\"";
			echo ">Administrador</option>
						</select>
					</td>
				</tr>
			";
		}
	}
	else
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
?>
				</table>
				<p style="text-align:center;"><button name="back">Voltar</button>
				<input type="submit" value="Submeter"></p>
			</form>
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
	</body>
</html>

<?
	if(isset($_POST['back']))
		redirect("./");
	
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$user_types = $_POST['user_type']; //user_types is an array of all the selects (different usernames) and the index is the user ID
		foreach($user_types as $i=>$user_type)
		{
			if(!$db->query('UPDATE user set user_type='.$user_type.' where rowid='.$i))
			{
				$error=$db->errorInfo();
				echo "Erro: " . $error[2];
			}
		}
		redirect('./');
		//is there a way to force update of $_SESSION['user_type'] on users already logged in?
	}
?>
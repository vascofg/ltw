<?php
	session_start();
	require_once 'common/functions.php';
	require_once 'db/db.php'; //in this file it's needed either way
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<2) //if not logged in or not admin, go away
		redirectmsg("./", 0);
	$username=$_GET['username'];
	if($_SERVER['REQUEST_METHOD'] != "POST") {
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
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div id="conteudo">
<?
	if(!empty($username))
		$stmt = $db->query('SELECT rowid, username, user_type FROM user WHERE username like \'%'.$_GET['username'].'%\'');
	else
		$stmt = $db->query('SELECT rowid, username, user_type FROM user');
	
	if($stmt){
		$stmt = $stmt->fetchAll();
		if(count($stmt)==0){ //if no results
			echo "<h4>Nenhum utilizador encontrado</h4>";
		}
		else {?>
			<form method="post">
				<table style="margin: auto;" border="1">
					<tr>
						<td>Username</td>
						<td>Tipo de Utilizador</td>
					</tr>
<?
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
?>
				</table>
				<p style="text-align:center;"><input type="submit" value="Submeter"></p>
			</form>
<?
		}
	}
	else
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
?>
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
	</body>
</html>

<?
	}
	else
	{
		$user_types = $_POST['user_type']; //user_types is an array of all the selects (different usernames) and the index is the user ID
		$ids = implode(',', array_keys($user_types)); //get all individual ids
		$sql = "UPDATE user SET user_type = CASE rowid ";
		foreach ($user_types as $id => $value) {
			$sql .= sprintf("WHEN %d THEN %d ", $id, $value); //when ID then Value
		}
		$sql .= "END WHERE rowid IN ($ids)";
		if(!$db->query($sql))
		{
			$error=$db->errorInfo();
			echo "Erro: " . $error[2];
		}
		else
			redirectmsg('./', 2);
		//is there a way to force update of $_SESSION['user_type'] on users already logged in?
	}
?>

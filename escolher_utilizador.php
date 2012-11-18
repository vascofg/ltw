<?php
	session_start();
	require_once 'common/functions.php';
	require_once 'db/db.php'; //in this file it's needed either way
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<2) //if not logged in or not admin, go away
		redirectmsg("./", 'Operação não permitida');
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
			<a href="./"><h1>Social News</h1></a>
			<h2>Escolher Utilizador</h2>
		</div>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li>
			</ul>
			<ul class="login">
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div id="conteudo">
<?php
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
			<form action="editar_perfil_utilizador.php" method="get">
				<table border="1" style="margin: auto;" id="utilizadores_encontrados">					
					<col width="60%">
					<col width="90%">
					<tr>
						<th>Username</th>
						<th>Tipo de Utilizador</th>
					</tr>
<?php
			foreach($stmt as $row)
			{
			
			 if($row['user_type']==0)
			{
				$name= "Utilizador";
			}
			if($row['user_type']==1)
			{
			$name= "Editor";
			}
			if($row['user_type']==2)
			{
			$name= "Administrador";
			}
			
			
				echo " 
					<tr>
						<td>
							<a href=editar_perfil_utilizador.php?id=".$row['rowid'].">".$row['username']."</a>
						</td>
						
						<td>
						$name
						</td>	
						
										
					</tr>
				";
			}
?>
				</table>
			</form>
<?php
		}
	}
	else
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
?>
		<p></p>
		</div>
		<div id="rodape">
			<p>Projecto 1 - Linguagens e Tecnologias Web @ FEUP - 2012</p>
		</div>
	</body>
</html>

<?php
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
			redirectmsg('./', 'Operação efectuada');
		//is there a way to force update of $_SESSION['user_type'] on users already logged in?
	}
?>

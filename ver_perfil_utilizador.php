<?php
	require_once 'common/functions.php';
	require_once 'db/db.php'; //in this file it's needed either way
	$username=$_GET['posted_by'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Ver Perfil de Utilizador</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
			<a href="./"><h1>Social News</h1></a>
			<h2>Ver Perfil de Utilizador</h2>
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
	{
		$stmt = $db->prepare('SELECT id, user_type FROM user WHERE username = :username');
		$stmt->bindparam(':username', $username);
	}
	else
	{
		$stmt = $db->query('SELECT id, user_type FROM user');
	}
	
	if($stmt->execute()){
		$stmt = $stmt->fetchAll();
		if(count($stmt)==0){ //if no results
			echo "<h4>Nenhum utilizador encontrado</h4>";
		}
		else {?>
			
			<table border="1" style="margin: auto;" id="detalhes_utilizador">					
				<col width="40%">
				<col width="60%">	
<?php				

				echo "<tr><th>Username</th><th>Tipo de Utilizador</th><tr>";
		
				$row = $stmt[0];	
					
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
				
				echo "<td>".$username."</td><td>".$name."</td>";
				
?>
				</table>
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
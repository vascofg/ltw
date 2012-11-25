<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	$id=$_GET['id'];
	if(empty($id)) //if no id set, go away
		redirectmsg("./", 'Operação não permitida');
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
				<li><a href="./">Voltar</a></li><?php
	if($id==$_SESSION['user_id'] || $_SESSION['user_type']==2) //if current user profile or admin
		echo "<li><a href=\"editar_perfil_utilizador.php?id=".$_SESSION['user_id']."\">Editar perfil</a></li>";
?>
			</ul>
			<ul class="login">
<?php
	echo "<li>Bem-vindo <a href=ver_perfil_utilizador.php?id=".$_SESSION['user_id'].">".$_SESSION['username']."</a></li><li><a href=\"logout.php\">Logout</a></li>";
?>
			</ul>
		</div>
		<div id="conteudo">
<?php
	$stmt = $db->prepare('SELECT username, user_type FROM user WHERE id = :id');
	if(!empty($id))
		$stmt->bindparam(':id', $id);
	else
		$stmt->bindparam(':id', $_SESSION['user_id']);
	
	if($stmt->execute()){
		$stmt = $stmt->fetchAll();
		if(count($stmt)==0){ //if no results
			echo "<h4>Nenhum utilizador encontrado</h4>";
		}
		else {
			
		echo "<table border=\"1\" style=\"margin: auto;\" id=\"utilizadores_encontrados\">					
			<col width=\"60%\">
			<col width=\"90%\">		
			<tr><th>Username</th><th>Tipo de Utilizador</th><tr>";

			$row = $stmt[0];	
				
			switch($row['user_type'])
			{
				case 0: $type="Utilizador";
						break;
				case 1: $type="Editor";
						break;
				case 2: $type="Administrador";
						break;
			}
			echo "<td>".$row['username']."</td><td>".$type."</td>
			</table>";
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
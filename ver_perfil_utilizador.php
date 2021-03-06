<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	$id=$_GET['id'];
	if(empty($id)) //if no id set, go away
		redirectMsg("./", 'Operação não permitida');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Ver Perfil de Utilizador</title>
		<link rel="stylesheet" href="common/style.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="common/messages.js"></script>
	</head>
	<body>
<?php
	showHeader('Ver Perfil de Utilizador');
?>
		<div id="menu">
			<ul>
				<a href="./"><img src="common/home.png"></a><?php
	if($id==$_SESSION['user_id'] || admin()) //if current user profile or admin
		echo "<li><a href=\"editar_perfil_utilizador.php?id=".$_SESSION['user_id']."\">Editar perfil</a></li>";
?>
			</ul>
<?php
	showLoginMenu();
	echo "</div>";
	showMessage();
	echo "<div id=\"conteudo\">";
	
	$stmt = $db->prepare('SELECT username, user_type FROM user WHERE id = :id');
	if(!empty($id))
		$stmt->bindparam(':id', $id);
	else
		$stmt->bindparam(':id', $_SESSION['user_id']);
	
	if($stmt->execute()){
		$stmt = $stmt->fetchAll();
		if(count($stmt)==0){ //if no results
			echo "<h5>Nenhum utilizador encontrado.</h5>";
		}
		else {
			
		echo "<table border=\"1\" style=\"margin: auto;\" id=\"utilizadores_encontrados\">					
			<col width=\"40%\">
			<col width=\"40%\">		
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
	echo "<p></p>
		</div>";
	showFooter();
?>	
	</body>
</html>
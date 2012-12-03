<?php
	require_once 'common/functions.php';
	require_once 'db/db.php'; //in this file it's needed either way
	if(!loggedIn() || !admin()) //if not logged in or not admin, go away
		redirectMsg("./", 'Operação não permitida');
	$username=$_GET['username'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Alterar permissões</title>
		<link rel="stylesheet" href="common/style.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="common/messages.js"></script>
	</head>
	<body>
<?php
	showHeader('Escolher Utilizador');
?>
		<div id="menu">
			<ul>
				<a href="./"><img src="common/home.png"></a>
			</ul>
<?php
	showLoginMenu();
	echo "</div>";
	showMessage();
	echo "<div id=\"conteudo\">";

	if(!empty($username))
		$stmt = $db->query('SELECT id, username, user_type FROM user WHERE username like \'%'.$_GET['username'].'%\'');
	else
		$stmt = $db->query('SELECT id, username, user_type FROM user');
	
	if($stmt){
		$stmt = $stmt->fetchAll();
		if(count($stmt)==0){ //if no results
			echo "<h5>Nenhum utilizador encontrado.</h5>";
		}
		else {?>
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
							<a href=editar_perfil_utilizador.php?id=".$row['id'].">".$row['username']."</a>
						</td>
						
						<td>
						$name
						</td>	
						
										
					</tr>
				";
			}
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
	echo "<p></p>
		</div>";
	showFooter();
?>
	</body>
</html>

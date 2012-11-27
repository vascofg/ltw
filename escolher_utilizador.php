<?php
	require_once 'common/functions.php';
	require_once 'db/db.php'; //in this file it's needed either way
	if(!loggedin() || !admin()) //if not logged in or not admin, go away
		redirectmsg("./", 'Operação não permitida');
	$username=$_GET['username'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Alterar permissões</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
<?php
	showheader('Escolher Utilizador', true);
?>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li>
			</ul>
<?php
	showloginmenu()
?>
		</div>
		<div id="conteudo">
<?php
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
			</form>
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
	showfooter();
?>
	</body>
</html>
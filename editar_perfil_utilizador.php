<?php
	session_start();
	require_once 'common/functions.php';
	require_once 'db/db.php'; //in this file it's needed either way
	if(!isset($_SESSION['username'])) //if not logged in, go away
		redirectmsg("./", 'Operação não permitida');
	$id=(int)$_GET['id'];
	if($_SERVER['REQUEST_METHOD'] != "POST") {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Editar Perfil do Utilizador</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
			<a href="./"><h1>Social News</h1></a>
			<h2>Editar Perfil do Utilizador</h2>
		</div>
		<div id="menu">
			<ul>
			 	<li><a href="./">Voltar</a></li><li>Apagar Utilizador</li>
			</ul>
			<ul class="login">
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div id="conteudo">
<?php
		$stmt = $db->prepare('SELECT rowid, username, user_type FROM user WHERE rowid = :id');
		$stmt->bindparam(':id',$id);
		$stmt->execute();
	
	if($stmt){
		$stmt = $stmt->fetchAll();
		if(count($stmt)==0){ //if no results
			echo "<h4>Nenhum utilizador encontrado</h4>";
		}
		else {
		
		$row=$stmt[0];
		?>
			<form method="post" action="guardar_alteracoes_perfil_utilizador.php?username=<?php echo $row['username'];?>">
				<table style="margin: auto;">
				<tr>
						<td>Username: </td>
						<td><?php echo $row['username'];?></td>
				<?php
				if($_SESSION['user_type'] == 2)
				{	?>
				<tr>	<td>Tipo de Utilizador:</td> 
						<td>
<?php
						echo "<select name = \"user_type\">
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
							</select>";
						
					?>
					</td>
					</tr>
				
				<?php
				}
					if($_SESSION['user_type'] != 2)
					{
				?>
					<tr>
					<td>Password actual: </td><td><input type="password" name="pass_actual"></td>
					</tr>
					<?php
					}
					?>
				
					<tr>
					<td>Nova Password: </td><td><input type="password" name="nova_pass"></td>
					</tr>
					<tr>
					<td>Confirmar Nova Password: </td><td><input type="password" name="nova_pass_2"></td>	
					</tr>
					<?php
						
		}
?>
				</table>
				<p style="text-align:center;"><input type="submit" value="Submeter"></p>
			</form>
<?php
		}
	else
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
	}
?>
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
	</body>
</html>
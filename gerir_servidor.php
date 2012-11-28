<?php
	require_once 'common/functions.php';
	require_once 'db/db.php';
	if(!loggedin() || !admin()) //if not logged in or not admin, go away
		redirectmsg("./", 'Operação não permitida');
	$username=$_GET['username'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Gerir servidores</title>
		<link rel="stylesheet" href="common/style.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="common/messages.js"></script>
	</head>
	<body>
<?php
	showheader('Gerir servidores');
?>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li><li><a href="adicionar_servidor.php">Adicionar servidor</a></li>
			</ul>
<?php
	showloginmenu();
	echo "</div>";
	showmessage();
	echo "<div id=\"conteudo\">";
	$stmt = $db->query('SELECT rowid, * FROM server order by name');
	
	if($stmt){
		$stmt = $stmt->fetchAll();
		if(count($stmt)==0){ //if no results
			echo "<h5>Nenhum servidor encontrado.</h5>";
		}
		else {?>
			<table border="1" style="margin: auto;" id="utilizadores_encontrados">					
				<tr>
					<th>Nome</th>
					<th>URL</th>
					<th></th>
				</tr>
<?php
			foreach($stmt as $row)
			{

				echo " 
					<tr>
						<td>
							".$row['name']."
						</td>
						
						<td>
							<a target=\"_blank\" href=\"".$row['url']."\">".$row['url']."</a>
						</td>	
						<td style=\"text-align:center;\">
							<a href=\"apagar_servidor.php?id=".$row['rowid']."\">Remover</a>
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
	showfooter();
?>
	</body>
</html>

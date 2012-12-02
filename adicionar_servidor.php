<?php
	require_once 'common/functions.php';
	if(!loggedIn() || !admin()) //if not logged in or not admin, go away
		redirectMsg("./", 'Operação não permitida');
	if($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['url']) || empty($_POST['url'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Adicionar servidor</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
<?php
	showHeader('Adicionar servidor');
?>
		<div id="menu">
			<ul>
				<a href="./"><img src="common/home.png"></a>
			</ul>
<?php
	showLoginMenu()
?>
		</div>
		<div id="conteudo">
			<form method="post">
				<table style="margin: auto;">
					<tr>
						<td>Nome</td>
						<td><input type="text" size="50" name="name"></td>
					</tr>
					<tr>
						<td>URL</td>
						<td><input type="text" size="50" name="url" placeholder="URL para a raiz do site, incluindo http://"></td>
					</tr>
				</table>
				<p style="text-align:center;"><input type="submit" value="Submeter"></p>
			</form>
		</div>
<?php
	showFooter();
?>
	</body>
</html>

<?php
	}
	else
	{
		$name=$_POST['name'];
		$url=$_POST['url'];
		
		$name = strip_tags($name);
		$url = strip_tags($url);
		
		require_once 'db/db.php';
		$stmt = $db->prepare('INSERT INTO server values(?,?)');
		if($stmt->execute(array($name,$url)))
			redirectMsg("./gerir_servidor.php", 'Operação efectuada');
		else
		{
			$error=$db->errorInfo();
			if($error[1]==19)
				redirectmsg("gerir_servidor.php","Servidor com o mesmo nome / URL já registado");
			echo "Erro: " . $error[2];
		}
	}
?>

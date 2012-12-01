<?php
	require_once 'common/functions.php';
	if(!loggedin() || !admin()) //if not logged in or not admin, go away
		redirectmsg("./", 'Operação não permitida');
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
	showheader('Adicionar servidor');
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
	showfooter();
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
			redirectmsg("./gerir_servidor.php", 'Operação efectuada');
		else
		{
			$error=$db->errorInfo();
			echo "Erro: " . $error[2];
		}
	}
?>

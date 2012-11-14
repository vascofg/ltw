<?php
	session_start();
	require_once 'common/functions.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<1 || !isset($_GET['id'])) //if not logged in, not editor or no id set, go away
		redirectmsg("./", 0);
	$id = $_GET['id'];
	require_once 'db/db.php'; //in this file it's needed either way
	if($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['text']) || empty($_POST['text'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Editar Notícia</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
			<h1>Editar Notícia</h1>
			<h2>Edite uma notícia</h2>
		</div>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div id="conteudo">
			<form method="post">
				<table style="margin: auto;">
<?
	$stmt = $db->query('SELECT * FROM news where rowid = '.$id);
	if($stmt)
	{
		$result = $stmt -> fetchAll();
		$result = $result[0]; //first result
		echo "		<tr>
						<td>Título</td>
						<td><input type=\"text\" name=\"title\" value=\"".$result['title']."\"></td>
					</tr>
					<tr>
						<td>Texto</td>
						<td><textarea name=\"text\">".$result['text']."</textarea></td>
					</tr>";
	}
	else
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
?>
				</table>
				<p style="text-align:center;"><input type="submit" value="Submeter"></p>
			</form>
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
	</body>
</html>

<?php
	}
	else
	{
		$title=$_POST['title'];
		$text=$_POST['text'];
		
		$stmt = $db->prepare('UPDATE news SET title=?, text=? where rowid = ?');
		$stmt->execute(array($title, $text, $id));
		
		redirectmsg("./?id=" . $id, 2);
	}
?>

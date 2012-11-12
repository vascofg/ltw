<?php
	session_start();
	require_once 'common/functions.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<1 || !isset($_GET['id'])) //if not logged in, not editor or no id set, go away
		redirectmsg("./", 0);
	$id = $_GET['id'];
	if($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['text']) || empty($_POST['text']) || !isset($_POST['posted_by']) || empty($_POST['posted_by'])) {
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
		<div id="conteudo">
			<form method="post">
				<table style="margin: auto;">
<?
	$db = new PDO('sqlite:db/news.db');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
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
					</tr>
					<tr>
						<td>Poster</td>
						<td><input type=\"text\" name=\"posted_by\" value=\"".$result['posted_by']."\"></td>
					</tr>
					<tr>
						<td>URL</td>
						<td><input type=\"text\" name=\"url\" value=\"".$result['url']."\"></td>
					</tr>";
	}
	else
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
?>
				</table>
				<p style="text-align:center;"><input type="button" name="back" value="Voltar" onClick="javascript:location.href = './';">
				<input type="submit" value="Submeter"></p>
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
		$posted_by=$_POST['posted_by'];
		$url=$_POST['url'];
		
		$db = new PDO('sqlite:db/news.db');
		$stmt = $db->prepare('UPDATE news SET title=?, text=?, posted_by=?, url=? where rowid = ?');
		$stmt->execute(array($title, $text, $posted_by, $url, $id));
		
		redirectmsg("./?id=" . $id, 2);
	}
?>

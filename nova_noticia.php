<?php
	session_start();
	require_once 'common/functions.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<1) //if not logged in or not editor, go away
		redirectmsg("./", 0);
	if($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['text']) || empty($_POST['text']) || !isset($_POST['posted_by']) || empty($_POST['posted_by'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Nova Notícia</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
			<h1>Nova Notícia</h1>
			<h2>Inserir uma nova notícia</h2>
		</div>
		<div id="conteudo">
			<form method="post">
				<table style="margin: auto;">
					<tr>
						<td>Título</td>
						<td><input type="text" name="title"></td>
					</tr>
					<tr>
						<td>Texto</td>
						<td><textarea name="text"></textarea></td>
					</tr>
					<tr>
						<td>Poster</td>
						<td><input type="text" name="posted_by"></td>
					</tr>
					<tr>
						<td>URL</td>
						<td><input type="text" name="url"></td>
					</tr>
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
		$stmt = $db->prepare('INSERT INTO news values(?, ?, ?, ?, ?)');
		$stmt->execute(array($title, time(), $text, $posted_by, $url));
		
		redirectmsg("./?id=" . $db->lastInsertID(), 2);
	}
?>

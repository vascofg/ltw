<?php
	session_start();
	require_once 'common/functions.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<1) //if not logged in or not editor, go away
		redirectmsg("./", 0);
	if($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['text']) || empty($_POST['text'])) {
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
			<a href="./"><h1>Social News</h1></a>
			<h2>Inserir notícia</h2>
		</div>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li>
			</ul>
			<ul class="login">
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div id="conteudo">
			<form method="post">
				<table style="margin: auto;">
					<tr>
						<td>Título</td>
						<td><input type="text" name="title"></td>
					</tr>
					<tr>
						<td style="vertical-align:top;">Texto</td>
						<td><textarea cols="60" rows="15" name="text"></textarea></td>
					</tr>
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
		
		require_once 'db/db.php';
		$stmt = $db->prepare('INSERT INTO news values(?, ?, ?, ?)');
		$stmt->execute(array($title, time(), $text, $_SESSION['username']));
		
		redirectmsg("./?id=" . $db->lastInsertID(), 2);
	}
?>

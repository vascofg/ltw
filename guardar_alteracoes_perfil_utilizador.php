<?php
	session_start();
	require_once 'common/functions.php';
	require_once 'db/db.php'; //in this file it's needed either way
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<2) //if not logged in or not admin, go away
		redirectmsg("./", 0);
	$id=$_POST['id'];

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
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
	</body>
</html>
<?php
	session_start();
	require_once 'common/functions.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<2) //if not logged in or not admin, go away
		redirectmsg("./", 0);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Procurar utilizador</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
			<h1>Procurar utilizador</h1>
			<h2>Insira parte do username a alterar<br>Vazio lista todos os utilizadores</h2>
		</div>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li>
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div id="conteudo">
			<form method="get" action="alterar_permissoes.php">
				<table style="margin: auto;" border="1">
					<tr>
						<td>Username</td>
						<td><input type="text" name="username"</td>
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
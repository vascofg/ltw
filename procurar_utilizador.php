<?php
	require_once 'common/functions.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<2) //if not logged in or not admin, go away
		redirectmsg("./", 'Operação não permitida');
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
			<a href="./"><h1>Social News</h1></a>
			<h2>Procurar utilizadores</h2>
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
			<form method="get" action="escolher_utilizador.php">
				<table style="margin: auto;">
					<tr>
						<td>Username: </td>
						<td><input type="text" name="username"</td>
					</tr>
				</table>
				<p style="text-align:center;"><input type="submit" value="Submeter"></p>
			</form>
		</div>
		<div id="rodape">
			<p>Projecto 1 - Linguagens e Tecnologias Web @ FEUP - 2012</p>
		</div>
	</body>
</html>
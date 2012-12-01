<?php
	require_once 'common/functions.php';
	if(!loggedin() || !admin()) //if not logged in or not admin, go away
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
<?php
	showheader('Procurar utilizadores');
?>
		<div id="menu">
			<ul>
				<a href="./"><img src="common/home.png"></a>
			</ul>
<?php
	showloginmenu()
?>
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
<?php
	showfooter();
?>
	</body>
</html>
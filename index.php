<?php
	session_start();
	$id = $_GET['id'];
	require_once 'db/db_query.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Projecto 1</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
			<h1>Projecto 1</h1>
			<h2>Linguagens e Tecnologias Web</h2>
		</div>
		<!--<div id="menu">
			<ul>
				<li><a href="">Políticas</a></li>
				<li><a href="">Desporto</a></li>
				<li><a href="">Mundo</a></li>
				<li><a href="">Educação</a></li>
				<li><a href="">Sociedade</a></li>
			</ul>
		</div>-->
		<div id="conteudo">
			
<?	
	foreach($news as $row) {?>
	<div class="noticia">
		<h3><? echo $row['title'];?></h3>
		<img src="http://ipsumimage.appspot.com/300x200,ff7700" alt="300x200">
		<p><? echo $row['text'];?></p>
		<p><? echo "Data: ".date('d/m/Y H:i:s', $row['date']);?></p>
		<p><? echo "Submetida por: ".$row['posted_by'];?></p>
		<p><? echo "URL: ".$row['url'];?></p>
		<ul>
		<?
			if(isset($id) && !empty($id))
				echo "<li><a href=./>Ver Todas</a></li>";
			else
				echo "<li><a href=\"?id=".$row['id']."\">Ver Notícia</a></li>";
			if($_SESSION['user_type']>0)
				echo " <li><a href=\"editar_noticia.php?id=".$row['id']."\">Editar</a></li>
					   <li><a href=\"apagar_noticia.php?id=".$row['id']."\">Apagar</a></li>";?>
		<!--<li><a href="comentarios1.html">comentarios 
		<?
			//echo "(" . $row['count'] . ")";
		?>(0)</a></li>
		<li><a href="partilhar1.html">partilhar</a></li>-->
		
		</ul>
	</div>
	<?}?>
		</div>
		<div id="rodape">
			<p>
			<?
			if(isset($_SESSION['user_type']))
			{
				if($_SESSION['user_type']>0)
					echo "<a href=\"nova_noticia.php\">Inserir Nova Notícia</a> | ";
				if($_SESSION['user_type']==2)
					echo "<a href=\"alterar_permissoes.php\">Alterar permissões de utilizadores</a> | ";
				echo "<a href=\"logout.php\">Logout</a>";
			}
			else
				echo "<a href=\"login.php\">Login</a>";
			?>
			</p>
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
	</body>
</html>

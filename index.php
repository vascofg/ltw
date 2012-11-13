<?php
	session_start();
	$id = $_GET['id'];
	require_once 'db/news_query.php';
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
		<div id="menu">
			<ul>
<?
	if(isset($_SESSION['user_type']))
	{
		if($_SESSION['user_type']>0)
			echo "<li><a href=\"nova_noticia.php\">Inserir Nova Notícia</a></li>";
		if($_SESSION['user_type']==2)
			echo "<li><a href=\"alterar_permissoes.php\">Alterar permissões de utilizadores</a></li>";
		echo "<li><a href=\"logout.php\">Logout</a></li>";
	}
	else
		echo "<li><a href=\"login.php\">Login</a></li>";
?>
			</ul>
		</div>
		<div id="conteudo">
			
<?	
	foreach($news as $row) {?>
	<div class="noticia">
		<h3><? echo $row['title'];?></h3>
		<img src="common/placeholder.png" alt="300x200">
<?
	if(isset($_GET['id'])) //only display text and details on detailed view (one news item)
		echo 	"<p class=\"newsbody\">".nl2br/*convert newlines in database to <br>*/($row['text'])."</p>
				<div class=\"newsdetails\">
					Submetida por: ".$row['posted_by']."<br>
					URL: ".$row['url']."<br>";
	else
		echo 	"<br><br>
				<div class=\"newsdetails\">";
	if(date('dmY') == date('dmY', $row['date'])) //if news is from today, display only time, otherwise display date and time
		echo date('H:i', $row['date']);
	else
		echo date('d/m/Y H:i', $row['date']);
	echo 	"</div>
			<ul>";
	if(isset($id) && !empty($id))
		echo "<li><a href=./>Ver Todas</a></li>";
	else
		echo "<li><a href=\"?id=".$row['id']."\">Ver Notícia</a></li>";
	if($_SESSION['user_type']>0)
		echo " <li><a href=\"editar_noticia.php?id=".$row['id']."\">Editar</a></li>
			   <li><a href=\"apagar_noticia.php?id=".$row['id']."\">Apagar</a></li>";?>		
		</ul>
	</div>
	<?}?>
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
<?
		//display messages
		if(isset($_GET['msgid']))
		{
			$msgid=$_GET['msgid'];
			echo "<script type=\"text/javascript\">";
			switch($msgid)
			{
				case 0:	echo "alert(\"Operação não permitida\");";
						break;
				/*case 1:	echo "alert(\"Login efectuado\");";
						break;*/ //annoying
				case 2: echo "alert(\"Operação efectuada\");";
						break;
			}
			echo "</script>"; 
		}
?>
	</body>
</html>
<?php
	session_start();
	$id = $_GET['id'];
	require_once 'db/news_query.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Social News</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
<?	if(!empty($id))
		echo "<a href=\"./\"><h1>Social News</h1></a>";
	else
		echo "<h1>Social News</h1>";?>
			<h2>Linguagens e Tecnologias Web</h2>
		</div>
		<div id="menu">
			<ul>
<?
	if(isset($_SESSION['user_type']))
	{
		if($_SESSION['user_type']>0)
			echo "<li><a href=\"nova_noticia.php\">Inserir notícia</a></li>";
		if($_SESSION['user_type']==2)
			echo "<li><a href=\"procurar_utilizador.php\">Alterar permissões de utilizadores</a></li>";
		echo "<li><a href=\"logout.php\">Logout</a></li>";
	}
	else
		echo "<li><a href=\"login.php\">Login</a></li>";
?>
			</ul>
		</div>
		<div id="conteudo">
			
<?	
	if(count($news)==0) //if no results
		echo "<h4>Nenhuma notícia encontrada</h4>";
	else {
	foreach($news as $row) {

	if(empty($id))
		echo "<div class=\"noticia\">
		<h3><a href=\"?id=".$row['id']."\">".$row['title']."</a></h3>
		<a href=\"?id=".$row['id']."\"><img src=\"common/placeholder.jpg\" alt=\"300x200\"></a>
		<br><br>
		<div class=\"newsdetails\">";
	else
		echo "<div class=\"noticia\">
		<h3>".$row['title']."</h3>
		<a href=\"common/placeholder.jpg\" target=_blank><img src=\"common/placeholder.jpg\" alt=\"300x200\"></a>
		<p class=\"newsbody\">".nl2br/*convert newlines in database to <br>*/($row['text'])."</p>
		<div class=\"newsdetails\">
			Submetida por: ".$row['posted_by']."<br>";
		//only display text and details on detailed view (one news item)
		
	if(date('dmY') == date('dmY', $row['date'])) //if news is from today, display only time, otherwise display date and time
		echo "Hoje, ".date('H:i', $row['date']);
	elseif(date('dmY', time()-86400) == date('dmY', $row['date'])) //yesterday (1 day = 86400 seconds)
		echo "Ontem, ".date('H:i', $row['date']);
	else
		echo date('d/m/Y, H:i', $row['date']);
	echo 	"</div>
			<ul>";
	if(!empty($id))
		echo "<li><a href=./>Ver Todas</a></li>";
	else
		echo "<li><a href=\"?id=".$row['id']."\">Ver Notícia</a></li>";
	if($_SESSION['user_type']>0)
		echo " <li><a href=\"editar_noticia.php?id=".$row['id']."\">Editar</a></li>
			   <li><a href=\"apagar_noticia.php?id=".$row['id']."\">Apagar</a></li>";?>		
		</ul>
	</div>
	<?}
	}?>
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

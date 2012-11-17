<?php
	session_start();
	$id = $_GET['id'];
	$tag = $_GET['tag'];
 	require_once 'common/functions.php';
 	if(!empty($id) && !empty($tag)) //tag AND id? no sir	
		redirect('./');
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
<?	if(!empty($id) || !empty($tag)) //show link if not on all news listing
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
		{
			echo "<li><a href=\"nova_noticia.php\">Inserir notícia</a></li>";
		}

		if($_SESSION['user_type']==2)
		{
			echo "<li><a href=\"procurar_utilizador.php\">Gerir utilizadores</a></li>"
			."<li><a href=\"obter_noticias.php\">Obter notícias</a></li>";
		}
		
		echo "<li><a href=\"editar_perfil_utilizador.php?id=".$_SESSION['user_id']."\">Editar perfil</a></li>";
	}
	else
	{
		echo "<li></li>";
	}
?>
			</ul>
			<ul class="login"><?
				if(isset($_SESSION['user_type']))
					echo "<li><a href=\"logout.php\">Logout</a></li>";
				else
					echo "<li><a href=\"login.php\">Login</a></li>";?>
			</ul>
		</div>
		<div id="conteudo">
			
<?	
	if(!empty($tag))
 		echo "<h4>#".$tag."</h4>";
	if(count($news)==0) //if no results
		echo "<h4>Nenhuma notícia encontrada</h4>";
	else {
	foreach($news as $i=>$row) {
      if($row['id']==$news[$i-1]['id']) //if repeating news (because of tags)
        echo " <a href=\"./?tag=".$row['tagname']."\">#".$row['tagname']."</a>";
      else
      {
        if(empty($id))
          echo "<div class=\"noticia\">
          <h3><a href=\"?id=".$row['id']."\">".$row['title']."</a></h3>
          <a href=\"?id=".$row['id']."\"><img src=\"common/placeholder.jpg\" alt=\"300x200\"></a>
          <div class=\"newsdetails\">
            <br />";
        else
          echo "<div class=\"noticia\">
          <h3>".$row['title']."</h3>
          <a href=\"common/placeholder.jpg\" target=_blank><img src=\"common/placeholder.jpg\" alt=\"300x200\"></a>
          <div class=\"newsbody\">".nl2br/*convert newlines in database to <br>*/($row['text'])."</div>
          <div class=\"newsdetails\">
            <br />
            Submetida por: ".$row['posted_by']."<br>";
          //only display text and details on detailed view (one news item)
          
        if(date('dmY') == date('dmY', $row['date'])) //if news is from today, display only time, otherwise display date and time
          echo "Hoje, ".date('H:i', $row['date']);
        elseif(date('dmY', time()-86400) == date('dmY', $row['date'])) //yesterday (1 day = 86400 seconds)
          echo "Ontem, ".date('H:i', $row['date']);
        else
          echo date('d/m/Y, H:i', $row['date']);
        if($row['tagname']!="")
          echo "</div><div class=\"newstags\"><a href=\"./?tag=".$row['tagname']."\">#".$row['tagname']."</a>"; //first tag (close news details and start tags div)
      }
      if($row['id']!=$news[$i+1]['id']) { //if next row not a repeat, then close this new
        echo   "</div>
            <ul>";
        if(!empty($id))	
          echo "<li><a href=./>Ver Todas</a></li>";
       else
          echo "<li><a href=\"?id=".$row['id']."\">Ver Notícia</a></li>";
        if($_SESSION['user_type']>0)
      echo " <li><a href=\"editar_noticia.php?id=".$row['id']."\">Editar</a></li>
        <li><a href=\"apagar_noticia.php?id=".$row['id']."\">Apagar</a></li>";    
	echo "</ul>
	</div>";
	}
}
	}?>
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
<?
		//display messages
		if(isset($_SESSION['msg']))
		{
			echo "<script type=\"text/javascript\">alert(\"".$_SESSION['msg']."\")</script>";
			unset($_SESSION['msg']);
		}
?>
	</body>
</html>

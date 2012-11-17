<?php
	session_start();
	require_once 'common/functions.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<2) //if not logged in or not administrator, go away
		redirectmsg("./", 'Operação não permitida');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Obter Notícias</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
			<a href="./"><h1>Social News</h1></a>
			<h2>Obter notícias de noutros servidores</h2>
		</div>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li>
			</ul>
			<ul style="display:inline;" class="login"> <!-- had to define style because there was a bug in Chrome where the display:inline wouldnt work from the css file-->
				<li><a href="logout.php">Logout</a></li>
			</ul>
		</div>
		<div id="conteudo">
<?
	if($_SERVER['REQUEST_METHOD'] != "POST" || empty($_POST['url'])) {?>
			<form method="post">
				<table style="margin: auto;">
					<tr>
						<td>URL: </td>
						<td><input size="50" type="text" name="url" placeholder="URL para a raiz do site, incluindo http://"></td>
					</tr>
					<tr>
						<td>Data Inicial: </td>
						<td><input size="50" type="text" name="start_date"></td>
					</tr>
					<tr>
						<td>Data Final: </td>
						<td><input size="50" type="text" name="end_date"></td>
					</tr>
					<tr>
						<td>Tags: </td>
						<td><input size="50" type="text" name="tags" placeholder="Tags separadas por espaço"></td>
					</tr>
				</table>
				<p style="text-align:center;"><input type="submit" value="Submeter"></p>
			</form>
<?
	}
	else {
		$url = $_POST['url'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		if(!empty($start_date))
			$start_date=date('c',strtotime($start_date)); //convert date to iso 8601
		if(!empty($end_date))
			$end_date=date('c',strtotime($end_date)); //convert date to iso 8601
		$tags = $_POST['tags'];
		$url = $url."/api/news.php?start_date=".urlencode($start_date)."&end_date=".urlencode($end_date)."&tags=".urlencode($tags); //urlencode converts special characters to their hex value for passing through url
		if(!$json = json_decode(file_get_contents($url)))
			echo "<h4>Falhou a obtenção do JSON</h4>";
		else{
			if($json->{'result'}=="error")
				echo "<h4>O JSON reportou um erro com o código ".$json->{'code'}.": \"".$json->{'reason'}."\"</h4>";
			else
			{
				$json_news = $json->{'data'};
				if(count($json_news)==0) //if no results
					echo "<h4>Nenhuma notícia encontrada</h4>";
				else
				{
					foreach($json_news as $row)
					{
						echo "<div class=\"noticia\">
						  <h3>".$row->{'title'}."</h3>
						  <div class=\"newsbody\">".nl2br/*convert newlines in json to <br>*/($row->{'text'})."</div>
						  <div class=\"newsdetails\">
							<br />
							Submetida por: ".$row->{'posted_by'}."<br>";
						  //only display text and details on detailed view (one news item)
						 
						$date = strtotime($row->{'date'});
						if(date('dmY') == date('dmY', $date)) //if news is from today, display only time, otherwise display date and time
						  echo "Hoje, ".date('H:i', $date);
						elseif(date('dmY', time()-86400) == date('dmY', $date)) //yesterday (1 day = 86400 seconds)
						  echo "Ontem, ".date('H:i', $date);
						else
						  echo date('d/m/Y, H:i', $date);
						echo "<br></div>";
						if(!empty($row->{'tags'}))
						{
							echo "<div class=\"newstags\">";
							foreach($row->{'tags'} as $i=>$json_tag)
							{
								echo "#".$json_tag;
								if(++$i != count($row->{'tags'}))
									echo " ";
							}
							echo "</div>";
						}
						echo "<br></div>";
					}
				}
			}
		}
	}
?>
		</div>
		<div id="rodape">
			<p>Projecto 1 de LTW @ FEUP - 2012</p>
		</div>
	</body>
</html>
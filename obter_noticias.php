<?php
	require_once 'common/functions.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<2) //if not logged in or not administrator, go away
		redirectmsg("./", 'Operação não permitida');
	if(!isset($_POST['submit_insert'])) {
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
			<h2>Obter notícias de outros servidores</h2>
		</div>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li>
			</ul>
			<ul style="display:inline;" class="login"> <!-- had to define style because there was a bug in Chrome where the display:inline wouldnt work from the css file-->
<?php
	echo "<li>Bem-vindo <a href=ver_perfil_utilizador.php?id=".$_SESSION['user_id'].">".$_SESSION['username']."</a></li><li><a href=\"logout.php\">Logout</a></li>";
?>
			</ul>
		</div>
		<div id="conteudo">
<?php
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
<?php
	}
	else {
		$url = $_POST['url'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		
		if(!empty($start_date))
			$start_date=preg_replace('/\+\d\d:\d\d/','',date('c',strtotime($start_date))); //convert date to iso 8601
		else
			$start_date=preg_replace('/\+\d\d:\d\d/','',date('c',0));
		if(!empty($end_date))
			$end_date=preg_replace('/\+\d\d:\d\d/','',date('c',strtotime($end_date))); //convert date to iso 8601
		else
			$end_date=preg_replace('/\+\d\d:\d\d/','',date('c',time()));
		$tags = $_POST['tags'];
		$url = $url."/api/news.php?start_date=".urlencode($start_date)."&end_date=".urlencode($end_date)."&tags=".urlencode($tags); //urlencode converts special characters to their hex value for passing through url
		if(!$json = json_decode(file_get_contents($url)))
			echo "<h5>Falhou a obtenção do JSON!</h5>";
		else{
			if($json->{'result'}=="error")
				echo "<h5>O JSON reportou um erro com o código ".$json->{'code'}.": \"".$json->{'reason'}."\"</h5>";
			else
			{
				$json_news = $json->{'data'};
				$_SESSION['json_news']=$json_news; //improve? (won't be unset if no news are added - unsetting on index)
				if(count($json_news)==0) //if no results
					echo "<h5>Nenhuma notícia encontrada!</h5>";
				else
				{
					echo "<form method=\"post\">";
					foreach($json_news as $i => $row)
					{
						echo "<div class=\"noticia\">
						  <h3><input type=\"checkbox\" name=\"news[".$i."]\"> ".stripslashes($row->{'title'})."</h3>
						  <div class=\"newsbody\">".nl2br/*convert newlines in json to <br>*/(stripslashes($row->{'text'}))."</div>
						  <div class=\"newsdetails\">
							<br />
							URL: <a href=\"".stripslashes($row->{'url'})."\">".stripslashes($row->{'url'})."</a><br>
							Submetida por: ".stripslashes($row->{'posted_by'})."<br>";
						  //only display text and details on detailed view (one news item)
						 
						$date = strtotime($row->{'date'});
						echo displaydate($date);
						echo "<br></div>";
						if(!empty($row->{'tags'}))
						{
							echo "<div class=\"newstags\">";
							foreach($row->{'tags'} as $i=>$json_tag)
							{
								echo "#".stripslashes($json_tag);
								if(++$i != count($row->{'tags'}))
									echo " ";
							}
							echo "</div>";
						}
						echo "<br></div>";
					}
					echo "<p style=\"text-align:center;\"><input type=\"submit\" name=\"submit_insert\" value=\"Inserir\"></p>
					</form>";
				}
			}
		}
	}
?>
		</div>
		<div id="rodape">
			<p>Projecto 1 - Linguagens e Tecnologias Web @ FEUP - 2012</p>
		</div>
	</body>
</html>
<?php }
else { //insert selected news
	require_once 'db/db.php';
	if(isset($_SESSION['json_news']))
	{
		$json_news = $_SESSION['json_news'];

		foreach($_POST['news'] as $i => $row)
		{
			$stmt = $db->prepare('INSERT INTO news values(null, ?, ?, ?, ?, ?, ?)');
			if($stmt->execute(array($json_news[$i]->{'title'}, strtotime($json_news[$i]->{'date'}), $json_news[$i]->{'text'}, $json_news[$i]->{'posted_by'}, $json_news[$i]->{'url'}, $_SESSION['username'])))
			{
				$news_id=$db->lastInsertID();
				
				if(!empty($json_news[$i]->{'tags'}))
				{
					$sql = "INSERT INTO 'tag'
								SELECT '".$news_id."' as 'news_id', '".$json_news[$i]->{'tags'}[0]."' as 'tag' ";
					foreach ($json_news[$i]->{'tags'} as $j=>$tag)
					{
						if ($j < 1) continue; //skip first tag
						$sql .= sprintf("UNION SELECT '%d', '%s' ", $news_id, $json_news[$i]->{'tags'}[$j]);
					}
					if(!$db->query($sql))
					{	
						$error=$db->errorInfo();
						if($error[1]!=19) //skip duplicate tag error
						{
							echo "Erro: " . $error[2];
							exit;
						}
					}
				}
			}
			else
			{
				$error=$db->errorInfo();
				echo "Erro: " . $error[2];
			}
		}
		unset($_SESSION['json_news']);
		redirectmsg("./", 'Operação efectuada');
	}
	else
		redirectmsg("./", 'Erro a obter o JSON');
}
 ?>
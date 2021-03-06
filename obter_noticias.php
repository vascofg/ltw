<?php
	require_once 'common/functions.php';
	if(!loggedIn() || !admin()) //if not logged in or not administrator, go away
		redirectMsg("./", 'Operação não permitida');
	require_once('db/db.php');
	if(!isset($_POST['submit_insert'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Obter Notícias</title>
		<link rel="stylesheet" href="common/style.css">
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script src="https://raw.github.com/jquery/jquery-ui/master/ui/i18n/jquery.ui.datepicker-pt.js"></script>
		<script src="common/get_news.js"></script>
	</head>
	<body>
<?php
	showHeader('Obter notícias de outros servidores');
?>
		<div id="menu">
			<ul>
				<a href="./"><img src="common/home.png"></a>
			</ul>
			<ul style="display:inline;" class="login"> <!-- had to define style because there was a bug in Chrome where the display:inline wouldnt work from the css file-->
<?php
	echo "<li>Bem-vindo <a href=ver_perfil_utilizador.php?id=".$_SESSION['user_id'].">".$_SESSION['username']."</a></li><li><a href=\"logout.php\">Logout</a></li>";
?>
			</ul>
		</div>
		<div id="conteudo">
<?php
	if($_SERVER['REQUEST_METHOD'] != "POST") {
		
		$stmt = $db->query('SELECT count(*) as count FROM server');
	
		if(!$stmt)
		{
			$error=$db->errorInfo();
			echo "Erro: " . $error[2];
		}
		else{
			$stmt = $stmt->fetch();
			if((int)$stmt["count"]==0) //if no results
				echo "<h5>Nenhum servidor encontrado.</h5>";
			else {?>
			<form method="post">
				<table style="margin: auto;">
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
		}
	}
	else {
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
		
		//Get server URLs
		$stmt = $db->query('SELECT rowid,* FROM server order by name');
	
		if(!$stmt)
		{
			$error=$db->errorInfo();
			echo "Erro: " . $error[2];
		}
		else{
			$stmt = $stmt->fetchAll();
			if(count($stmt)==0) //if no results
			echo "<h5>Nenhum servidor encontrado.</h5>";
			else {
			
			foreach($stmt as $serveri => $server)
			{
			
			echo "<div class=\"server\"><h4 class=\"servertitle\">".$server['name']." - <a target=\"_blank\" href=\"".$server['url']."\">".$server['url']."</a><div class=\"arrow\">˅</div></h4>";
		
			$url = $server['url']."/api/news.php?start_date=".urlencode($start_date)."&end_date=".urlencode($end_date)."&tags=".urlencode($tags); //urlencode converts special characters to their hex value for passing through url
			if(!$json = json_decode(file_get_contents($url)))
				echo "<h5>Falhou a comunicação com o servidor!</h5>";
			else{
				if($json->{'result'}=="error")
					echo "<h5>O servidor reportou um erro com o código ".$json->{'code'}.": \"".$json->{'reason'}."\"</h5>";
				else
				{
					$json_news = $json->{'data'};
					$_SESSION['json_news'][$serveri]=$json_news;
					if(count($json_news)==0) //if no results
						echo "<h5>Nenhuma notícia encontrada!</h5>";
					else
					{
						echo "<form method=\"post\">";
						foreach($json_news as $i => $row)
						{
							echo "<div class=\"noticia\">
								<h3><input type=\"checkbox\" name=\"news[".$serveri."][".$i."]\"> ".strip_tags(stripslashes($row->{'title'}))."<div class=\"arrow\">˅</div></h3>
								<div class=\"newsbody\" style=\"display:none;\">".nl2br/*convert newlines in json to <br>*/(strip_tags(stripslashes($row->{'text'})))."</div>
								<div class=\"newsdetails\">
								<br />
								URL: <a target=\"_blank\" href=\"".strip_tags(stripslashes($row->{'url'}))."\">".strip_tags(stripslashes($row->{'url'}))."</a><br>
								Submetida por: ".strip_tags(stripslashes($row->{'posted_by'}))."<br>";
								//only display text and details on detailed view (one news item)
							 
							$date = strtotime($row->{'date'});
							echo displayDate($date);
							echo "<br></div>";
							if(!empty($row->{'tags'}))
							{
								echo "<div class=\"newstags\">";
								foreach($row->{'tags'} as $i=>$json_tag)
								{
									echo "#".strip_tags(stripslashes($json_tag));
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
			echo "</div>";
		}
		echo "<p style=\"text-align:center;\"><input type=\"submit\" name=\"submit_insert\" value=\"Inserir\"></p>
						</form>";
	}
	}
	}
	echo "</div>";
	showFooter();
?>
	</body>
</html>
<?php }
else { //insert selected news
	if(!isset($_POST['news']))
		redirectMsg("./", 'Nenhuma notícia seleccionada');
	if(isset($_SESSION['json_news']))
	{
		$json_news = $_SESSION['json_news'];

		foreach($_POST['news'] as $serveri => $server)
		{
			foreach($server as $i => $row)
			{
				$stmt = $db->prepare('INSERT INTO news values(null, ?, ?, ?, ?, ?, ?)');
				if($stmt->execute(array(strip_tags($json_news[$serveri][$i]->{'title'}), strtotime($json_news[$serveri][$i]->{'date'}), strip_tags($json_news[$serveri][$i]->{'text'}), strip_tags($json_news[$serveri][$i]->{'posted_by'}), strip_tags($json_news[$serveri][$i]->{'url'}), strip_tags($_SESSION['username']))))
				{
					$news_id=$db->lastInsertID();
				
					if(!empty($json_news[$serveri][$i]->{'tags'}))
					{
						$sql = "INSERT INTO 'tag'
									SELECT '".$news_id."' as 'news_id', '".strip_tags($json_news[$serveri][$i]->{'tags'}[0])."' as 'tag' ";
						foreach ($json_news[$serveri][$i]->{'tags'} as $j=>$tag)
						{
							if ($j < 1) continue; //skip first tag
							$sql .= sprintf("UNION SELECT '%d', '%s' ", $news_id, strip_tags($json_news[$serveri][$i]->{'tags'}[$j]));
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
		}
		redirectMsg("./", 'Operação efectuada');
	}
	else
		redirectMsg("./", 'Erro a obter o JSON');
}
 ?>

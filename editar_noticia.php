<?php
	require_once 'common/functions.php';
	if(!isset($_SESSION['username']) || $_SESSION['user_type']<1 || !isset($_GET['id']) || empty($_GET['id'])) //if not logged in, not editor or no id set, go away
		redirectmsg("./", 'Operação não permitida');
	$id = (int)$_GET['id'];
	require_once 'db/db.php'; //in this file it's needed either way
	if($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['text']) || empty($_POST['text'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Editar Notícia</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
		<div id="cabecalho">
			<a href="./"><h1>Social News</h1></a>
			<h2>Editar notícia</h2>
		</div>
		<div id="menu">
			<ul>
				<li><a href="./">Voltar</a></li>
			</ul>
			<ul class="login">
<?php
	echo "<li>Bem-vindo <a href=ver_perfil_utilizador.php?id=".$_SESSION['user_id'].">".$_SESSION['username']."</a></li><li><a href=\"logout.php\">Logout</a></li>";
?>
			</ul>
		</div>
		<div id="conteudo">
<?php
	$stmt = $db->query('SELECT id, title, date, text, posted_by, tag.rowid as tagid, tagname FROM news LEFT JOIN tag ON news.id=tag.news_id where id = '.$id);
	if($stmt)
	{
		$result = $stmt -> fetchAll();
		if(count($result)>0) {
			echo "<form method=\"post\">
						<table style=\"margin: auto;\">		
						<tr>
							<td>Título</td>
							<td><input type=\"text\" size=\"50\" name=\"title\" value=\"".stripslashes($result[0]['title'])."\"></td>
						</tr>
						<tr>
							<td style=\"vertical-align:top;\">Texto</td>
							<td><textarea cols=\"60\" rows=\"15\" name=\"text\">".stripslashes($result[0]['text'])."</textarea></td>
						</tr>";
			if(!empty($result[0]['tagname'])){ //if any tags already exist
				echo "<tr>
						<td style=\"vertical-align:top;\">Apagar<br>tags</td>
						<td>";
				foreach($result as $row)
					echo "<input type=\"checkbox\" name=\"tag[".$row['tagid']."]\"> ".stripslashes($row['tagname'])."<br>";
				echo "</td></tr>";
			}
			echo "<tr>
					<td>Novas<br>tags</td>
					<td><input type=\"text\" size=\"50\" name=\"tags\" placeholder=\"Tags separadas por espaço\"></td>
				</tr>
				</table>
				<p style=\"text-align:center;\"><input type=\"submit\" value=\"Submeter\"></p>
			</form>";
		}
		else
			echo "<h4>Nenhuma notícia encontrada</h4>";
	}
	else
	{
		$error=$db->errorInfo();
		echo "Erro: " . $error[2];
	}
?>
		</div>
		<div id="rodape">
			<p>Projecto 1 - Linguagens e Tecnologias Web @ FEUP - 2012</p>
		</div>
	</body>
</html>

<?php
	}
	else
	{
		$title=$_POST['title'];
		$text=$_POST['text'];
		$tag=$_POST['tag'];
		$tags=$_POST['tags'];
		
		$stmt = $db->prepare('UPDATE news SET title=?, text=? where id = ?');
		if($stmt->execute(array($title, $text, $id)))
		{
			if(!empty($tag))
			{
				$sql='DELETE FROM tag where rowid in ('.implode(',',array_keys($tag)).')';
				if(!$db->query($sql))
				{
					$error=$db->errorInfo();
					echo "Erro: " . $error[2];
					exit;
				}
			}
			
			$tags=preg_replace('/\s\s+/', ' ', $tags); //remove extra spaces
			if(!empty($tags)&& $tags!=' ')
			{
				$tag_array = explode(' ',$tags);
				$tag_array = array_filter($tag_array); //delete empty fields
				$sql = "INSERT INTO 'tag'
							SELECT '".$id."' as 'news_id', '".$tag_array[0]."' as 'tag' ";
				foreach ($tag_array as $i=>$tag)
				{
					if ($i < 1) continue; //skip first tag
					$sql .= sprintf("UNION SELECT '%d', '%s' ", $id, $tag_array[$i]);
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
			redirectmsg("./?id=" . $id, 'Operação efectuada');
		}
		else
		{
			$error=$db->errorInfo();
			echo "Erro: " . $error[2];
		}
	}
?>

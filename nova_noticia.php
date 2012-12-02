<?php
	require_once 'common/functions.php';
	if(!loggedIn() || user()) //if not logged in or not editor, go away
		redirectMsg("./", 'Operação não permitida');
	if($_SERVER['REQUEST_METHOD'] != "POST" || !isset($_POST['title']) || empty($_POST['title']) || !isset($_POST['text']) || empty($_POST['text'])) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Nova Notícia</title>
		<link rel="stylesheet" href="common/style.css">
	</head>
	<body>
<?php
	showHeader('Inserir notícia');
?>
		<div id="menu">
			<ul>
				<a href="./"><img src="common/home.png"></a>
			</ul>
<?php
	showLoginMenu()
?>
		</div>
		<div id="conteudo">
			<form method="post">
				<table style="margin: auto;">
					<tr>
						<td>Título</td>
						<td><input type="text" size="50" name="title"></td>
					</tr>
					<tr>
						<td style="vertical-align:top;">Texto</td>
						<td><textarea cols="60" rows="15" name="text"></textarea></td>
					</tr>
					<tr>
						<td>Tags</td>
						<td><input type="text" size="50" name="tags" placeholder="Tags separadas por espaço"></td>
					</tr>
				</table>
				<p style="text-align:center;"><input type="submit" value="Submeter"></p>
			</form>
		</div>
<?php
	showFooter();
?>
	</body>
</html>

<?php
	}
	else
	{
		$title=$_POST['title'];
		$text=$_POST['text'];
		$tags=$_POST['tags'];
		
		$tags=preg_replace('/\s\s+/', ' ', $tags); //remove extra spaces
		if(!empty($tags)&& $tags!=' ')
		{
			$tag_array = explode(' ',$tags);
			$tag_array = array_filter($tag_array); //delete empty fields
		}
			
		require_once 'db/db.php';
		$stmt = $db->prepare('INSERT INTO news values(null, ?, ?, ?, ?, null, null)');
		if($stmt->execute(array(strip_tags($title), time(), strip_tags($text), $_SESSION['username'])))
		{
			$news_id=$db->lastInsertID();
			
			if(!empty($tags)&& $tags!=' ')
			{
				$sql = "INSERT INTO 'tag'
							SELECT '".$news_id."' as 'news_id', '".strip_tags($tag_array[0])."' as 'tag' ";
				foreach ($tag_array as $id=>$tag)
				{
					if ($id < 1) continue; //skip first tag
					$sql .= sprintf("UNION SELECT '%d', '%s' ", $news_id, strip_tags($tag_array[$id]));
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
			redirectMsg("./?id=" . $news_id, 'Operação efectuada');
		}
		else
		{
			$error=$db->errorInfo();
			echo "Erro: " . $error[2];
		}
	}
?>

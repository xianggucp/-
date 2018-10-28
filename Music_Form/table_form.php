<?php 
	$contents = file_get_contents('list.json');
	$data = json_decode($contents, true);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="base.css">
</head>
<body>
	<div class="header w"><h1>音乐列表</h1></div>
	<div class="w">
	<table cellspacing="0" cellpadding="0" calss="list">
		<tr>
			<td>名称</td>
			<td>作者</td>
			<td>图片</td>
			<td>作品</td>
			<td></td>
		</tr>
		<?php foreach ($data as $item): ?>
			<tr>
				<td> <?php echo $item['title'] ?></td>
				<td> <?php echo $item['name'] ?></td>
				<td> 
				<?php foreach ($item['image'] as  $val): ?>
					<img src="<?php echo $val ?>" style="width:50px;">				
				<?php endforeach ?>
				</td>

				<td> <audio src="<?php echo $item['audio'] ?>"  controls="controls"></audio></td>	
				<td><a href="delete.php?id=<?php echo $item['id'] ?>">删除</a>
				<a href="edit.php?id=<?php echo $item['id'] ?>">编辑</a>
				</td>
			</tr>
		<?php endforeach ?>

	</table>
	<button><a href="add.php">添加<a></button>
	</div>
</body>
</html>
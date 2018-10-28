<?php 

	if(empty($_GET['id'])){
		exit ("<h1>选择编辑的条目</h1>");
	}
	
		$id = $_GET['id'];
		setcookie('edit',$id);
		$data = json_decode(file_get_contents('list.json'),true);
	foreach ($data as $val) {
		if($val['id']===$id){
			$edit = $val;	
			
		}
	}
	if($_SERVER['REQUEST_METHOD']==='POST'){
	     $title = $_POST['title'];
	     $name = $_POST['name'];
	     //用户想编辑图片或者音乐文件
	    $images = $_FILES['image'];
		
		for( $i = 0; $i < count($images['error']); $i++){
			if($images['error'][$i]!== UPLOAD_ERR_OK){
				$GLOBALS['message'] = '提交正确的文件';
				return;
			}
				$ext = pathinfo($images['name'][$i], PATHINFO_EXTENSION);

				$image_limit = array('jpg','png','gif');
			//除了用后缀名以外，也可以用文件中type
			if(!in_array($ext,$image_limit)){
			 $GLOBALS['message'] = '提交正确的图片格式';
				return;
			}
				$image_id = uniqid();
				$target = './uploads/'.$image_id.'.'.$ext;
				$tmp = $images['tmp_name'][$i];

		 	if(!move_uploaded_file($tmp,$target)){
		 		$GLOBALS['message'] = '文件提交失败';
		 		return;
		 	}

		 	$image_dir[] = '/Music_Form'.substr($target, 1);
		 	
	 	}
	 	var_dump($image_dir);
	    foreach ($data as $val) {
			if($val['id']===$id){
				$index = array_search($val,$data);
				$data[$index]['title'] = $title;
				$data[$index]['name'] = $name;
				$data[$index]['image'] = $image_dir;
 				file_put_contents('list.json',json_encode($data));
				header('Location: table_form.php');
			}
			
		}
	}
 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="base.css">
</head>
<body>

<div class="form">
	<form action=" <?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
<table>
	  <?php if (isset($message)): ?> 
	  	<tr ><td colspan="2" style="background-color:red;color:white"> <?php echo $message; ?></td></tr> 
	  	<!-- 跨列合并，合并2个单元格，删除被合并的td，  rowspan 跨行合并，删除被合并那一列的单元格td -->
	 <?php endif ?> 
	 <?php if (isset($success)): ?> 
	  	<tr ><td colspan="2" style="background-color:green;color:white"> <?php echo $success; ?></td></tr> 
	  	<!-- 跨列合并，合并2个单元格，删除被合并的td，  rowspan 跨行合并，删除被合并那一列的单元格td -->
	 <?php endif ?>
	  
	<tr>
		<td><label for="a">名称</label></td><td><input type="text" name="title" id="a" value= " <?php echo $edit['title'] ?>" autocomplete="off"></td></tr>
	<tr>
		<td><label for="b">作者</label></td><td><input type="text" name="name" id="b" value= "<?php echo  $edit['name'] ?>" autocomplete="off"></td></tr>
	<tr>	
		<td><label for="c">图片</label></td><td><input type="file" name="image[]" multiple id="c" ></td></tr>
	<tr>	
		<td><label for="d">作品</label></td><td><input type="file" name="audio" id="d"></td></tr>
	
</table>
	<button>保存</button>
	</form>
</div>	
</body>
</html>
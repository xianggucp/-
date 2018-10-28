<?php 
	function addMusic(){
		if(empty($_POST['title'])){
			$GLOBALS['message'] = '输入音乐标题';
				return;
		}
		if(empty($_POST['name'])){
			$GLOBALS['message'] = '输入作者';
				return;
		}

		//确认能接受到图片提交这个image文件域，防止通过开发选项修改这个文件域
	
		if(empty($_FILES['image'])){  
			$GLOBALS['message'] = '提交正确的文件';
			return;
		}
			$images = $_FILES['image'];
			 //由于有两个文件域，所FILES会显示一个二维数组，Image和Audio,这里取Image数组
			 //操作，不然始终要使用 $_FILES['image'][]
			//var_dump($images);
			//array(5) { ["name"]=> string(17) "1536726502(1).png" ["type"]=> string(9) "image/png" ["tmp_name"]=> string(27) "C:\Windows\Temp\phpFFBB.tmp" ["error"]=> int(0) ["size"]=> int(46156) }
		//上一步判断提交的表单里是否有文件域，此处判断文件是否上传成功；
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
				$id = uniqid();
				$target = './uploads/'.$id.'.'.$ext;
				$tmp = $images['tmp_name'][$i];

		 	if(!move_uploaded_file($tmp,$target)){
		 		$GLOBALS['message'] = '文件提交失败';
		 		return;
		 	}

		 	$image_dir[] = '/Music_Form'.substr($target, 1);
		 	

		}


		if(empty($_FILES['audio'])){  
			$GLOBALS['message'] = '提交正确的文件';
			return;
		}
			$audio = $_FILES['audio'];
			
		//array(5) { ["name"]=> string(24) "小幸运 -田馥甄.mp3" ["type"]=> string(9) "audio/mp3" ["tmp_name"]=> string(27) "C:\Windows\Temp\phpC9F4.tmp" ["error"]=> int(0) ["size"]=> int(4248365) }
		if($audio['error'] !== UPLOAD_ERR_OK){
			$GLOBALS['message'] = '提交音乐失败';
				return;
		}
		if($audio['size'] > 20*1024*1024){
			$GLOBALS['message'] = '音乐文件过大,失败';
				return;
		}
		$audio_ext = pathinfo($audio['name'], PATHINFO_EXTENSION);
		$audio_limit = array('mp3','wma');
		if(!in_array($audio_ext,$audio_limit)){
			$GLOBALS['message'] = '提交正确的音乐格式';
				return;
		}
		$audio_target = './uploads/'.$audio['name'];
		$audio_tmp = $audio['tmp_name'];
		if(!move_uploaded_file($audio_tmp,$audio_target)){
		 		$GLOBALS['message'] = '文件提交失败';
		 		return;
		 	}
		$audio_dir = '/Music_Form'.substr($audio_target, 1);

		/*=========== 保存数据 ===========*/
		// 获取原有的数据
			$data = json_decode(file_get_contents('list.json'),true);
		// 新的数据
			$pic = array(
			'id' => $id,
			'title' => $_POST['title'],
			'name' => $_POST['name'],
			'image' => $image_dir,
			'audio' => $audio_dir
			//根目录就指的是站点所在的文件夹。
			);
			var_dump($pic);
		// 添加到新的数据中
		 $data[] = $pic;
		 // 把新数据保存到 json中
		 file_put_contents('list.json',json_encode($data));
		 
		 header('Location: table_form.php');
		 
	}
	if($_SERVER['REQUEST_METHOD']==='POST'){
	  addMusic();

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
	<form action=" <?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
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
		<td><label for="a">名称</label></td><td><input type="text" name="title" id="a" value= " <?php echo isset($_POST['title'])? $_POST['title']:'' ?>" autocomplete="off"></td></tr>
	<tr>
		<td><label for="b">作者</label></td><td><input type="text" name="name" id="b" value= "<?php echo  isset($_POST['name'])?$_POST['name']:'' ?>" autocomplete="off"></td></tr>
	<tr>	
		<td><label for="c">图片</label></td><td><input type="file" name="image[]" multiple id="c" autocomplete="off"></td></tr>
	<tr>	
		<td><label for="d">作品</label></td><td><input type="file" name="audio" id="d"></td></tr>
	
</table>
	<button>添加</button>
	</form>
</div>	
</body>
</html>
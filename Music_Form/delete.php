<?php 
$info = $_GET['id'];
//array(1) { ["id"]=> string(13) "5bd519830a293" }
$data = json_decode(file_get_contents('list.json'),true);
//$data是一个二维的数组，遍历所有的数组
foreach ($data as $val) {
	
	//string(1) "1" string(1) "2" string(13) "5bd519830a293" string(13) "5bd51e7bdd4b7"
if($val['id']===$info){
//根据ID找到了想要删除的那个数组，
// array_search(val,$data) 根据val这个值，在$data数组中查找到相应的键或者下标，是从DATA中删

	$index = array_search($val,$data);

	array_splice($data,$index,1); //返回的被删除的数
//反序列化生成新的json数据
   $new_json = json_encode($data);
   file_put_contents('list.json',$new_json);
	}
}
header('Location: table_form.php');
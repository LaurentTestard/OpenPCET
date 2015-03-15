<?php 
$path = 'tmp/';
$uploadfile = $path . basename($_FILES['file']['name']);
if (!file_exists($path)) {
    mkdir($path, 0777, true);
}
if(move_uploaded_file($_FILES['file']['tmp_name'],$uploadfile)){
	json_encode($_FILES['file']);
}else{
	json_encode(array("fail"=>"true"));
}

?>
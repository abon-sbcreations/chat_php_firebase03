<?php
//echo json_encode($_FILES);
$filename = $_FILES['uploaded_image']['name'];
$ext = pathinfo($filename, PATHINFO_EXTENSION);
$sourcePath = $_FILES['uploaded_image']['tmp_name'];
$time = time();
$file_name = $time.".".$ext;

$targetPath = "uploaded_files/".$file_name; 
require_once 'ConnectDb.php';
$db = ConnectDb::getInstance();
$mysqli = $db->getConnection();
if(move_uploaded_file($sourcePath,$targetPath)){
    $sql = "INSERT INTO uploaded_file (image_name,article_id,created_at) VALUES ('{$file_name}',1,'{$time}')";
    $mysqli->query($sql);
    $mysqli->close();
    
    echo json_encode(['status'=>1,
        'full_path'=>'http://ftpuser01.iottraining.in/uploaded_files/'.$file_name,
        'file_name' => $file_name]);
} else{
    echo json_encode(['status'=>0]);
}

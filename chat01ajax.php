<?php
require_once 'ConnectDb.php';
$db = ConnectDb::getInstance();
$mysqli = $db->getConnection();
$row = $_POST['row'];
$sql = "INSERT INTO `chat` ( `from_id`, `to_id`,is_link, `txt_msg`, `time1`, `ip`, `is_valid`) VALUES ('{$row['from_id']}','{$row['to_id']}','{$row['link']}','{$row['message']}','{$row['current_time']}','{$row['ip']}','1');";
$mysqli->autocommit(FALSE);
if ($mysqli->query($sql) === TRUE) {
    echo json_encode(['status'=>1,'msg'=>'record saved successfully','sql'=>$sql]);
} else {
    echo json_encode(['status'=>1,'msg'=>$mysqli->error,'sql'=>$sql]);
}
$mysqli->commit();
$mysqli->close();
?>
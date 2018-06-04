<?php
$row = $_POST;
require_once 'ConnectDb.php';
$sql = "SELECT * from chat "
        ."where (from_id = '{$row['logged_id']}' and to_id = '{$row['current_id']}') "
        . "or (to_id = '{$row['logged_id']}' and from_id = '{$row['current_id']}') ORDER by time1 desc limit 6";
$db = ConnectDb::getInstance();
$mysqli = $db->getConnection();
$result = $mysqli->query($sql);
$rows = [];
while($row = $result->fetch_assoc()){
    $rows[] = $row;
}
echo json_encode($rows);
$mysqli->close();
?>
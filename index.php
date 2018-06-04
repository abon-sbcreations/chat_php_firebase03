<?php
ob_start();
require_once 'ConnectDb.php';
if($_POST){ 
    $db = ConnectDb::getInstance();
    $mysqli = $db->getConnection(); 
    $sql = "select * from user where username ='{$_POST['username']}'";
    $result = $mysqli->query($sql);
   $row =  $result->fetch_assoc();
   if(isset($row)){
       session_start();
       $_SESSION["logged_user"] = $row["id"];
         header("Location: chat01.php");
   }else{
       echo "<h1>ERROR</h1>";
   }
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Login Page 08
        </title>
        <link href="library/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="container>
	<div class="row>
    	<div class="col-md-4"></div>
    	<div class="col-md-4">
        	<form class="" style="margin-top:50px" method="post">
            	<input class="form-control" type="text" id="username" name="username" placeholder="Username" required><br>
                <input class="btn btn-info"  type="submit" value="Login">
            </form>
        </div>
    	<div class="col-md-4"></div>
    </div>
    </body>
</html>

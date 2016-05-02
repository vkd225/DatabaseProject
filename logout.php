<?php
session_start();
	$host        = "host=pdc-amd01.poly.edu";
	$port        = "port=5432";
	$dbname      = "dbname=ku336";
	$credentials = "user=ku336 password=e0eycb7p";

   	$conn = pg_connect( "$host $port $dbname $credentials"  );
   	if(!$conn)
   		{
      		echo "Error : Unable to open database\n";
   		}
    $userName=$_SESSION['user'];
	pg_query($conn,"select * from sp_logout_time('$userName')");
	unset($_SESSION['user']);
	unset($_SESSION['is_auth']);
	session_destroy()
	
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<META http-equiv="refresh" content="0;URL=login.php">
</head>
<body>

</body>
</html>
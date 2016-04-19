<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Settings Page</title>
	</head>
<body>
<h5> Privacy Settings </h5>
<form action="settings.php" method="Post"> 
	<table>
		<tr>
			<td><input type="radio" name="Privacy" id="Public" value="Public" > Public </td>
  		</tr>
  		<tr>
  			<td><input type="radio" name="Privacy" id="Friends" value="Friends"> Friends </td>
		</tr>
		<tr>
			<td><input type="radio" name="Privacy" id="FriendsOfFriends" value="FriendsOfFriends" > Friends of Friends </td>
  		</tr>
  			
	</table>

</form>

</body>
</html>
<?php
   $host        = "host=pdc-amd01.poly.edu";
   $port        = "port=5432";
   $dbname      = "dbname=ku336";
   $credentials = "user=ku336 password=e0eycb7p";

   $conn = pg_connect( "$host $port $dbname $credentials"  );
   	if(!$conn)
   		{
    		echo "Error : Unable to open database\n";
   		}
   if(isset($_POST["Public"]))
   		{
   			$privacy=3;
   		}
   	elseif(isset($_POST["Friends"]))
   		{
   			$privacy=2;
   		}
   	elseif(isset($_POST["Public"]))
   		{
   			$privacy=1;
   		}
   	$userName=$_SESSION['user'];
   	$stmt=pg_prepare($conn,"s","select sp_update_privacy($1,$2)");
	$sqlname="s";
	$result=pg_execute($conn,$sqlname,array($userName,$privacy));					
?>

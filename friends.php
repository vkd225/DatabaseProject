<?php
session_start();
?>
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
?>
<!DOCTYPE html>
<html>
<head>
	<title> Friends </title>
</head>
<body>
	<h1>FRIENDS</h1>
	<a href="profile.php">Profile</a>
	<a href="settings.php">Settings</a>
	<form id='friend' role='form' action='friends.php' method='post'>
		<button type="submit" id="logout" name="logout">logout</button> 
			
		<table>
		<tr><h3> My friends </h3></tr>
<?php
	$userName=$_SESSION['user'];
	$stmt1=pg_prepare($conn,"s","select * from sp_show_friend($1)");
	$sqlname1="s";
	$result1=pg_execute($conn,"s",array("$userName"));
	$rows1=pg_num_rows($result1);
	if ($rows1>0)
		{
			while ($row=pg_fetch_array($result1,NULL,PGSQL_NUM))
				{
					$_SESSION['friend']=$row[0];
?>
				<tr>
				<td><a href="friendsPage.php"><?php echo ($row[0]);?></a></td>

			  	</tr>
<?php	
				}
		}
	else
		{
			echo "You dont have any friends.Hey we are still there for you.";
		}		   
		  #	   
	$SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname1));
	pg_query($SQL1);
?>
		</table>
	

		<table>
			<tr>
				<td>
				<h3>Friend requests</h3>
<?php
	$stmt2=pg_prepare($conn,"s","select * from friendship where user_name=$1 and status=2 ");
	$sqlname2="s";
	$result2=pg_execute($conn,"s",array("$userName"));
	$rows2=pg_num_rows($result2);
	if ($rows2>0)
	   {
		while ($row=pg_fetch_array($result2,NULL,PGSQL_NUM))
			{
?>
				<tr>
					<td><input type="text" name="friendRequest" readonly="" value="<?php echo ($row[1]);?>"></input></td>
<?php
				if(isset($_POST['friendRequest']))
					{
						$friendId=$_POST['friendRequest'];
					}
?>
					<td><input type="submit" name="confirm" value="confirm"></input></td>
					<td><input type="submit" name="delete" value="delete"></input></td>
			  	</tr>
<?php	
			}
		}
	else
		{
			echo "You dont have any pending friend requests";
		}		   
		  #	   
	$SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname1));
	pg_query($SQL1);
?>
			

		</table>			
	</form>
</body>
</html>
<?php
	if(isset($_POST["confirm"]))
	{

		$stmt2=pg_prepare($conn,"s","select sp_accept_friend_request($1,$2,$3)  ");
		$sqlname2="s";
		pg_execute($conn,"s",array($userName,$friendId,1));
		header('location: friends.php');
		$SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname2));
		   pg_query($SQL1);
			
	}
	if (isset($_POST["delete"])) 
	{
		$stmt2=pg_prepare($conn,"s","select sp_delete_friend_request($1,$2)  ");
		$sqlname2="s";
		pg_execute($conn,"s",array($userName,$friendId));
		header('location: friends.php');
		$SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname2));
		   pg_query($SQL1);
	}
	if (isset($_POST['logout'])) 
	{
		session_unset();
		session_destroy();
		header('location: login.php');
	}
?>

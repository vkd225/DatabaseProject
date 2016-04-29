<?php
session_start();
	$privacyPublic="unchecked";
	$privacyFriend="unchecked";
	$privacyFOF="unchecked";

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
	$stmt1=pg_prepare($conn,"s","select privacy from users where user_name=$1");
	$sqlname1="s";
	$result1=pg_execute($conn,$sqlname1,array("$userName"));
	$rows1=pg_num_rows($result1);
	if ($rows1>0)
		{	
			$selected=pg_fetch_array($result1,0,PGSQL_NUM);
			$selectedPrivacy=$selected[0];	
			echo $selectedPrivacy;
			if ($selectedPrivacy==3) 
				{
					$privacyPublic='checked';
						
				}
			if ($selectedPrivacy==2) 
				{
					$privacyFriend='checked';
	 					
				}
			if ($selectedPrivacy==1) 
				{
					$privacyFOF='checked';
		   					
				}	
		}
	$SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname1));
	pg_query($SQL1);
?>
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
				<td><a href="profile.php">Profile</a></td>
			</tr>
			<tr>
				<td><a href="friends.php">Friends</a></td>
			</tr>
			
			<tr>
				<td><input type="radio" name="Privacy" id="Public" value="Public" <?php echo $privacyPublic;?>> Public </td>
	  		</tr>
	  		<tr>
	  			<td><input type="radio" name="Privacy" id="Friends" value="Friends" <?php print $privacyFriend;?>> Friends </td>
			</tr>
			<tr>
				<td><input type="radio" name="Privacy" id="FriendsOfFriends" value="FriendsOfFriends" <?php print $privacyFOF;?> >Friends of Friends </td>
	  		</tr>
	  		<tr>
	  			<td><button id="update" name="update" value="update">Update</button></td>
	  		</tr>
  			
		</table>

	</form>	

</body>
</html>
<?php
  
   	if (isset($_POST["update"])) 
   		{	
   			$selectedPrivacy=$_POST["Privacy"];
			   		
   			if($selectedPrivacy=="Public")
		   		{	
		   			$privacyPublic='checked';
		   			$privacy=3;
		   			
		   					   		}
	  	 	if($selectedPrivacy=="Friends")
		   		{	
		   			$privacyFriend='checked';
		   			$privacy=2;
		   		}
	   		if($selectedPrivacy=="FriendsOfFriends")
		   		{	
		   			$privacyFOF='checked';
		   			$privacy=1;
		   		
		   		}
   			  			
   			
   			$stmt=pg_prepare($conn,"s","select sp_update_privacy($1,$2)");
			$sqlname="s";
			$result=pg_execute($conn,$sqlname,array($userName,$privacy));					
		  			
   		}	
   		
   	
?>

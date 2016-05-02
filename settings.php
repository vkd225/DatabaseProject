<?php
session_start();
if (!isset($_SESSION["is_auth"])) 
	{

    	header("location: login.php");
		exit;

	}
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

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<title>Settings Page</title>
	</head>
<body data-spy="scroll" data-target=".navbar" data-spy="affix" data-offset="50">
	<div class="container">
		<div class="page-header text-center">
			<h1>Settings</h1>
		</div>
		<div class="row">
			<nav class="navbar navbar-default">
  				<div class="container-fluid">
				    <div class="navbar-header">
				      <a class="navbar-brand" href="profile.php">Techies</a>
				    </div>
				    <ul class="nav navbar-nav">
				      <li><a href="profile.php">Profile</a></li>
				      <li><a href="search.php">Search</a></li> 
				      <li><a href="friends.php">Friends</a></li> 
				    </ul>
				    <ul class="nav navbar-nav navbar-right">
				        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
				    </ul>
				  </div>
				</nav>
		</div>
	
	<form action="settings.php" method="Post"> 
		<div class="radio">
			<div class="row ">
				<div class="col-sm-3">
					<input type="radio"  name="Privacy" id="Public" value="Public" <?php echo $privacyPublic;?>> Public 
				</div>
	  		</div>
	  		<div class="row">
	  			<div class="col-sm-3">
	  				<input type="radio" name="Privacy" id="Friends" value="Friends" <?php print $privacyFriend;?>> Friends 
	  			</div>
			</div>
			<div class="row">
				<div class="col-sm-3">
				<input type="radio" name="Privacy" id="FriendsOfFriends" value="FriendsOfFriends" <?php print $privacyFOF;?> >Friends of Friends
				</div>
	  		</div>
	  		<div class="row">
	  			<div class="col-sm-3">
	  				<button id="update" class="btn btn-primary" name="update" value="update">Update</button>
	  			</div>
	  		</div>
	  	</div>	
  			
		

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

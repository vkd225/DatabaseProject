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
	$stmt1=pg_prepare($conn,"s","select * from users where user_name=$1");
	$sqlname1="s";
	$result1=pg_execute($conn,$sqlname1,array("$userName"));
	$rows1=pg_num_rows($result1);
	if ($rows1>0)
		{	
			$selected=pg_fetch_array($result1,0,PGSQL_NUM);
			$selectedPrivacy=$selected[6];	
			
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
		<script>
        function validate()
        	{

			    if(!document.getElementById("password").value==document.getElementById("password_confirmation").value)alert("Passwords do no match");
			    return document.getElementById("password").value==document.getElementById("password_confirmation").value;
			    return false;
		    }
		    </script>
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
				    <form class="navbar-form navbar-left" method="Post" role="search">
				        <div class="form-group">
				         	<input type="text" id="searchUser" name="searchUser" class="form-control" placeholder="Search Users">
				        </div>
				        <button type="submit" id="searchButton" name="searchButton" class="btn btn-default">Submit</button>
				    </form>
				    
				    <ul class="nav navbar-nav navbar-right">
				        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
				    </ul>
				  </div>
				</nav>
		</div>
	
	<form action="settings.php"  method="Post"> 
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
            			<input type="text" name="FirstName" id="FirstName" class="form-control input-sm" placeholder="First Name" maxlength="50" value="<?php echo $selected[0];?>" required/>
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="text" name="LastName" id="LastName" class="form-control input-sm" placeholder="Last Name" maxlength="50" value="<?php echo $selected[1];?>" required/>
					</div>
				</div>
			</div>

			<div class="form-group">
				<input type="UserName" name="UserName" id="UserName" readonly="" class="form-control input-sm" placeholder="Username" maxlength="50" value="<?php echo $selected[2];?>" required/>
			</div>

			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="password" id="password"  class="form-control input-sm" placeholder="Password" value="<?php echo $selected[2];?>" required/>
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-sm" placeholder="Confirm Password"/>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
            			<input type="text" name="City" id="City" class="form-control input-sm" placeholder="City" maxlength="50" value="<?php echo $selected[5];?>" required/>
					</div>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="number" name="Age" id="Age" min="15" max="100" class="form-control input-sm" placeholder="Age" value="<?php echo $selected[4];?>" required/>
					</div>
				</div>
			</div>
		
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
	  			<p>				</p>
	  				<input type="submit" id="update" class="btn btn-primary" name="update" value="update"></input>
	  			</div>
	  		</div>
	  	</div>	
  			
		

	</form>	

</body>
</html>
<?php
   if($_SERVER["REQUEST_METHOD"]=="POST")
   		{
	   		if (isset($_POST["update"]))
	   			{
	   				if(isset($_POST["password"]))
								{
									if (isset($_POST["password_confirmation"]))
										{
											if (($_POST['password'])==($_POST['password_confirmation']))
											 	{
													$password=$_POST['password'];	
												
						
								    				if(isset($_POST["FirstName"]))
														{
															$firstName= $_POST["FirstName"];
														}
													if(isset($_POST["LastName"]))
														{
															$lastName= $_POST["LastName"];
														}
													if(isset($_POST["UserName"]))
														{
															$userName= $_POST["UserName"];
														}
													
													if(isset($_POST["Age"]))
														{
															$age= $_POST["Age"];
														}
													if(isset($_POST["City"]))
														{
															 $city= $_POST["City"];
															 
														}
													 			   		
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
													$Insert_query=pg_query($conn,"select * from sp_update_user('$userName','$firstName','$lastName','$password','$age','$city',$privacy)");
													$message = "User details updated";
													echo "<script type='text/javascript'>alert('$message');</script>";
												 	echo ("<meta http-equiv='refresh' content='0;url=http://localhost/settings.php'>");
	   											}
	   										else
	   										 		{
	   													$message = "Passwords dont match";
														echo "<script type='text/javascript'>alert('$message');</script>";
	   												}		
   			  							}
   			  					}
   			  	}						
   			
				
		  			
   		
   	if (isset($_POST["searchButton"])) 
		{
			#echo "iam in search button";
			if (isset($_POST["searchUser"]))
				 {
				# code...ec
			#	 echo "i am in search user";
			
					$_SESSION["searchUser"]=$_POST["searchUser"];
					header('location: searchuser.php');
				}
		}
	}	
   		
   	
?>

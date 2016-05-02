<?php
session_start();
if (!isset($_SESSION["is_auth"])) 
	{

    	header("location: login.php");
		exit;

	}
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
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body data-spy="scroll" data-target=".navbar" data-spy="affix" data-offset="50">
	<div class="container">
		<div class="page-header text-center">
			<h1>Friends</h1>
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
				      <li><a href="settings.php">Settings</a></li> 
				    </ul>
				    <form class="navbar-form navbar-left" role="search">
				        <div class="form-group">
				         	<input type="text" id="searchUser" class="form-control" placeholder="Search friends">
				        </div>
				        <button type="submit" id="searchButton" class="btn btn-default">Submit</button>
				    </form>
				    <ul class="nav navbar-nav navbar-right">
				        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
				    </ul>
				  </div>
				</nav>
		</div>
			
	<form id='friend' role='form' action='friends.php' method='post'>
		<div class="panel panel-default">
		<div class="panel-body">
			<div class="row form-group">
				<div class="col-sm-6">
					<h3> My friends </h3>
				</div>	
				<div class="col-sm-6">
					<h3>Friend requests</h3>
				</div>
			</div>	
			<div class="table-responsive">		
			<table class="table">
				<tr>
					<td>
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
				<div class="row">
					<div class="col-sm-1">
						<a href="friendsPage.php"><?php echo ($row[0]);?></a>
					</div>
					<div class="col-sm-9">
						
					</div>
				</div>	
<?php	
				}
		}
	else
		{
			echo "You dont have any friends.";
		}		   
		  #	   
	$SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname1));
	pg_query($SQL1);
?>	
					</td>
					<td>						
<?php
	$stmt2=pg_prepare($conn,"s","select * from friendship where user_name=$1 or friend_id=$1 and status=2 ");
	$sqlname2="s";
	$result2=pg_execute($conn,"s",array("$userName"));
	$rows2=pg_num_rows($result2);
	if ($rows2>0)
	   {
		while ($row=pg_fetch_array($result2,NULL,PGSQL_NUM))
			{
?>
				<div class="row">
					<div class="col-sm-6">
						<input type="text" class="form-control" name="friendRequest" readonly="" value="<?php echo ($row[1]);?>"></input>
					</div>
				</div>		
<?php
				if(isset($_POST['friendRequest']))
					{
						$friendId=$_POST['friendRequest'];
					}
?>
				<div class="row">
					<div class="col-sm-2">
						<input type="submit" class="btn btn-success" name="confirm" value="confirm"></input>
					</div>
					<div class="col-sm-1">	
						<input type="submit" class="btn btn-danger" name="delete" value="delete"></input>
					</div>
					<div class="col-sm-9">
						
					</div>	
			  	</div>
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
			</td>
		</tr>	
	</table>
	</div>
	</div>
	</div>
	</form>
</body>
</html>
<?php
	if(isset($_POST["confirm"]))
	{

		$stmt2=pg_prepare($conn,"s","select sp_accept_friend_request($1,$2,$3)");
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

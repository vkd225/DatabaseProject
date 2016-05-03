<?php
session_start();
#if (!isset($_SESSION["is_auth"])) 
#	{
#
#   	header("location: login.php");
#		exit;


#	}

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
    $userName=$_SESSION['user'];
		   #profile from database
	$stmt=pg_prepare($conn,"s","select profile from user_profile where user_name=$1");
	$sqlname="s";
	$result=pg_execute($conn,$sqlname,array("$userName"));
	$rows=pg_num_rows($result);
		if ($rows>0)
	 	    {
		   	    echo "Welcome " . $userName;
		   		$profile=pg_fetch_result($result, 0, 0);
		    }
		else if($rows=0)
		    {
		 	  	echo"No such record exists";
		    }
	$SQL=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname));
	pg_query($SQL);
?>
<?php
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
				if (isset($_POST["searchButton"])) 
					{
					echo "iam in search button";
					if (isset($_POST["searchUser"]))
						{
						# code...ec
						 echo "i am in search user";
					
							$_SESSION["searchUser"]=$_POST["searchUser"];
							header('location: searchuser.php');
						}
					}
		}
?>



<!DOCTYPE>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<style type="text/css">
					.dropdown {
			    display:inline-block;
			    margin-left:20px;
			    padding:10px;
			  }


			.glyphicon-bell {
			   
			    font-size:1.5rem;
			  }

			.notifications {
			   min-width:420px; 
			  }
			  
			  .notifications-wrapper {
			     overflow:auto;
			      max-height:250px;
			    }
			    
			 .menu-title {
			     color:#ff7788;
			     font-size:1.5rem;
			      display:inline-block;
			      }
			 
			.glyphicon-circle-arrow-right {
			      margin-left:10px;     
			   }
			  
			   
			 .notification-heading, .notification-footer  {
			 	padding:2px 10px;
			       }
			      
			        
			.dropdown-menu.divider {
			  margin:5px 0;          
			  }

			.item-title {
			  
			 font-size:1.3rem;
			 color:#000;
			    
			}

			.notifications a.content {
			 text-decoration:none;
			 background:#ccc;

			 }
			    
			.notification-item {
			 padding:10px;
			 margin:5px;
			 background:#ccc;
			 border-radius:4px;
			 }




	</style>
</head>
<body data-spy="scroll" data-target=".navbar" data-spy="affix" data-offset="50">
	<div class="container">
	<div class="dropdown">
			<a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
			    <i class="glyphicon glyphicon-bell"></i>
			  </a>
			  
			  <ul class="dropdown-menu notifications" role="menu" area-labelledby="dLabel">
			    
			    <div class="notification-heading"><h4 class="menu-title">Notifications</h4>
			    </div>
			    <li class="divider"></li>
			   <div class="notifications-wrapper">
<?php
				
			    $result13=pg_query($conn,"select * from sp_check_new_profile_friends('$userName')");
			    $rows13=pg_num_rows($result13);
			    if ($rows13>0)
				   {
				   		while ($row=pg_fetch_array($result13,NULL,PGSQL_NUM))
							{
								
?>			   
							     <a class="content" href="friends">
							      
							       <div class="notification-item">
							        <h4 class="item-title"><?php echo $row[0]; ?></h4>
							        <p class="item-info">sent you a friend request </p>
							      </div>
							       
							    </a>
<?php
							}
					}
				else
					{
?>						<a class="content" href="#">
							      
							       <div class="notification-item">
							        <h4 class="item-title"><?php echo "No new friend requests"; ?></h4>
							        <p class="item-info"></p>
							      </div>
							       
						</a>
<?php
					}
							 
?>

<?php
				
			    $result11=pg_query($conn,"select * from sp_check_new_profile_friends('$userName')");
			    $rows11=pg_num_rows($result11);
			    if ($rows11>0)
				   {
				   		while ($row=pg_fetch_array($result11,NULL,PGSQL_NUM))
							{
								
?>			   
							     <a class="content" href="#">
							       <div class="notification-item">
							        	<h4 class="item-title"><?php echo $row[0]; ?></h4>
							        	<p class="item-info">changed his profile</p>
							      	</div>
							       
							    </a>
<?php
							}
					}
				else
					{
?>						<a class="content" href="#">
							      
							       <div class="notification-item">
							        <h4 class="item-title"><?php echo "No new profiles"; ?></h4>
							        <p class="item-info"></p>
							      </div>
							       
						</a>
<?php
					}
							 
?>
<?php
				
			    $result12=pg_query($conn,"select * from sp_check_new_diary_entry_friends('$userName')");
			    $rows12=pg_num_rows($result12);
			    if ($rows12>0)
				   {
				   		while ($row=pg_fetch_array($result12,NULL,PGSQL_NUM))
							{
								
?>			   
							     <a class="content" href="#">
							      
							       <div class="notification-item">
							        	<h4 class="item-title"><?php echo $row[0]; ?></h4>
							        	<p class="item-info">added a new diary entry</p>
							      	</div>
							       
							    </a>
<?php
							}
					}
				else
					{
?>						<a class="content" href="#">
							      
							       <div class="notification-item">
							        <h4 class="item-title"><?php echo "No new diary entries"; ?></h4>
							        <p class="item-info"></p>
							      </div>
							       
						</a>
<?php
					}
							 
?>							    	
   </div>
    <li class="divider"></li>
    
  </ul>
  
</div>

		<div class="page-header text-center">
			<h1>Techies</h1>
		</div>
				<div class="row">
			<nav class="navbar navbar-default">
  				<div class="container-fluid">
				    <div class="navbar-header">
				      <a class="navbar-brand" href="profile.php">Techies</a>
				    </div>
				    <ul class="nav navbar-nav">
				      <li><a href="setings.php">Settings</a></li>
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
	

<form method="post" action="profile.php">

	
		<div class="row">
			<div class="col-sm-12">
				<h4>My Profile:</h4>
				<textarea  class="form-control" style="resize:none" readonly=""><?php echo($profile);?></textarea>
			<div>
		</div >
		<div class="row">
			<div class="col-sm-2">
				<h4>Profile Comment</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-7">
				<textarea class="form-control" style="resize:none" name="comment"></textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<p>				</p>
				<input type="submit" name="comment_button" value="comment"></input>
				<p>				</p>
			</div>
			</div>

<?php
	$stmt2=pg_prepare($conn,"s","select * from sp_search_comments_by_commented_on($1)");
	$sqlname2="s";
	$result2=pg_execute($conn,"s",array("$userName"));
	$rows2=pg_num_rows($result2);
	   if ($rows2>0)
		   {
		   	while ($row=pg_fetch_array($result2,NULL,PGSQL_NUM))
				{
					$time_post=date("Y-M-d(g:i a)",strtotime($row[2]));
?>
			<div class="row">
				<div class="col-sm-2">
					<b><input type="text" class="form-control" name="commenter" readonly="" value="<?php echo ($row[0]);?>"></input></b>
				</div>
					<div class="col-sm-3">
						<textarea class="form-control" style="resize:none" readonly=""><?php echo($row[1]);?> </textarea>
						<p>				</p>
					</div>
				
					<div class="col-sm-2">
						<small><input type="text" class="form-control" name="time_posted_comment" readonly="" value="<?php echo ($time_post);?>"></input></small>
					</div>
		  	</div>
<?php
				}
			}
	$SQL2=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname2));
	pg_query($SQL2);
?>
        <!--The diary entry-->
	        <div class="row">
				<div class="col-sm-3">
					<h3>Diary Entry:</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<input type="text" class="form-control" name="title" id="diary_title" placeholder="Title of Diary Entry:" /></input>
					<p>				</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<input type="text" row ="3" class="form-control input-lg" name="body" id="diary_body" placeholder="Your Diary Entry !!!" /></input>
					<p>				</p>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-1">
					<input type="submit" name="post" value="post"></input>
					<p>				</p>
				</div>
			</div>
			<h4>Past Diary Entries:</h4>
<?php
	if($_SERVER['REQUEST_METHOD']=='POST')
		{
			if (isset($_POST["comment_button"]))
				{

					if (isset($_POST["comment"]))
						{
							$commenter=$userName;
							$Comment=$_POST["comment"];

							$stmt3=pg_prepare($conn,"s","select add_comment($1,$2,$3)");
							$sqlname3="s";
					   		$result3=pg_execute($conn,"s",array($userName,$commenter,$Comment));
					   		$SQL3=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname3));
					   		pg_query($SQL3);
					   		header("location:profile.php");

				   		}
			   	}
			if (isset($_POST["post"]))
				{

					if (isset($_POST["body"]))
						{
							$body=$_POST["body"];
							$title=$_POST["title"];

							$stmt5=pg_prepare($conn,"s","select sp_insert_user_diary($1,$2,$3)");
							$sqlname5="s";
					   		$result5=pg_execute($conn,"s",array($userName,$title,$body));
					   		$SQL5=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname5));
					   		pg_query($SQL5);
					   		header("location:profile.php");

				   		}
			   	}
		}
?>

<?php

	$stmt6=pg_prepare($conn,"s","select * from sp_post_diary_entry($1)");
	$sqlname6="s";
    $result6=pg_execute($conn,"s",array("$userName"));
    $rows6=pg_num_rows($result6);
    if ($rows6>0)
	   {
	   		while ($row=pg_fetch_array($result6,NULL,PGSQL_NUM))
				{
					$time_post=date("Y-M-d(g:i a)",strtotime($row[3]));
?>
					<div class="row">
						<div class="col-sm-3">
							<p>				</p>
							<input type="text" class="form-control" name="title_" readonly="" value="<?php echo ($row[1]);?>"></input>
						</div>
						<div class="col-sm-4">
							<p>				</p>
							<textarea class="form-control" style="resize:none" readonly=""><?php echo($row[2]);?> </textarea> 
						</div>
						<div class="col-sm-2">
							<p>				</p>
							<small><input type="text" class="form-control" style="resize:none" name="time_posted_comment" readonly="" value="<?php echo ($time_post);?>"></input></small>
						</div>
				  	</div>
				  	<div class="row">
						<div class="col-sm-3">
							<input type="text" class="form-control" name="<?php echo $row[0]."comment";?>" id="diary_body_comment" placeholder="comment on it."/></input>
						</div>
						<div class="row">
							<div class="col-sm-2">
								<p>				</p>
								<input type="submit" name="<?php echo $row[0]; ?>" value="comment"></input>	
							</div>
						</div>
					</div>
						
<?php
				}
		}
	$SQL6=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname6));
	pg_query($SQL6);





	if($_SERVER['REQUEST_METHOD']=='POST')
		{	
			$stmt8=pg_prepare($conn,"s","select * from sp_post_diary_entry($1)");
			$sqlname8="s";
			$result8=pg_execute($conn,"s",array("$userName"));

			$rows8=pg_num_rows($result8);
		  	if ($rows8>0)
		   		{
		   			while ($row=pg_fetch_array($result8,NULL,PGSQL_NUM))
						{
							echo("i AM IN WHILE ");
							if (isset($_POST[$row[0]]))
								{
									echo("i AM IN IF");
									if (isset($_POST[$row[0]."comment"]))
										{
											$body1=$_POST[$row[0]."comment"];
											$diaryentry_id=$row[0];
											$stmt7=pg_prepare($conn,"k","select sp_insert_user_diary_comment($1,$2,$3,$4)");
											$sqlname7="k";
									   		$result7=pg_execute($conn,"k",array($userName,$userName,$body1,$diaryentry_id));
									   		$SQL7=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname7));
				  						 	pg_query($SQL7);
		   							#header("location:profile.php");
				   						}
				   					break;

		   						}	
		   				}

		   		}



			$SQL8=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname8));
		    pg_query($SQL8);
		}
		
		$stmt10=pg_prepare($conn,"s","select * from sp_show_user_diary_comment($1)");
		$sqlname10="s";
		$result10=pg_execute($conn,"s",array("$userName"));
		$rows10=pg_num_rows($result10);
		if ($rows10>0)
		   {
		    	while ($row=pg_fetch_array($result10,NULL,PGSQL_NUM))
					{
						$time_post=date("Y-M-d(g:i a)",strtotime($row[2]));
?>
		  				<div class="row">
							<div class="col-sm-2">
								<p>				</p>
								<b><input type="text" class="form-control" name="commenter" readonly="" value="<?php echo ($row[0]);?>"></input></b>
							</div>
							<div class="col-sm-3">
								<p>				</p>
								<textarea class="form-control" style="resize:none" readonly=""><?php echo($row[1]);?> </textarea>
							</div>
							<div class="col-sm-2">
								<p>				</p>
								<input type="text" class="form-control" name="time_posted_comment" readonly="" value="<?php echo ($time_post);?>"></input>
							</div>
		  				</div>
<?php
					}
			}
		$SQL10=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname10));
		Pg_query($SQL10);

?>
	
</form>
</body>
</html>

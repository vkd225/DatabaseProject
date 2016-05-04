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
   	$userName=$_SESSION['user'];

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

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</head>
<body data-spy="scroll" data-target=".navbar" data-spy="affix" data-offset="50">
	<div class="container">
		<div class="page-header text-center"><h1>Search</h1></div>
		<div class="row">
			<nav class="navbar navbar-default">
  				<div class="container-fluid">
				    <div class="navbar-header">
				      <a class="navbar-brand" href="profile.php">Techies</a>
				    </div>
				    <ul class="nav navbar-nav">
				      <li><a href="profile.php">Profile</a></li>
				      <li><a href="friends.php">Friends</a></li> 
				      <li><a href="settings.php">Settings</a></li> 
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
		<form method="Post">
		<div class="panel panel-default">
		<div class="panel-body">
			<div class="row form-group">
				<div class="col-sm-12 text-center">
					<label class="text-center">Enter keyword here </label>
				</div>
			</div>	
			<div class="row form-group">	
				<div class="col-sm-12 text-center">
					<input class="form-control" name="searchText" id="searchText" required></input>
				</div>
			</div>
			
			<div class="row form-group">
				<div class="col-sm-12 text-center">
					<label class="text-center">Search What</label>
				</div>
			</div>
			<div class="row form-group">
					<div class="col-sm-12 text-center">
					<input  type="radio" name="SearchWhat" id="Profile" value="Profile" required > Profile 
		 			<input  type="radio" name="SearchWhat" id="DiaryEntry" value="DiaryEntry"> Diary Entry 
		 			</div>
					
			</div>	
		
			<div class="row form-group">
				<div class="col-sm-12 text-center">
					<label class="text-center">Search By</label>
				</div>
			</div>
			<div class="row form-group">	
				<div class="col-sm-12 text-center">
				<input type="radio" name="SearchBy" id="Friend" value="Friend" required> Friend 
 				<input type="radio" name="SearchBy" id="FriendOfFriend" value="FriendOfFriend" > Friends Of Friend 
 				<input type="radio" name="SearchBy" id="Everyone" value="Everyone" > Everyone 
				</div>
			</div>	

			<div class="row form-group">
				<div class="col-sm-12 text-center"><input class="btn btn-primary btn-block" type='submit' name='Search' value='Search'/></div>
			</div>
		</div>	
		</div>
		
<?php
		if (isset($_POST['Search']))
			{
				if(isset($_POST['searchText']))
					{		
						$selectedSearchBy=$_POST['SearchBy'];
						$selectedSearchWhat=$_POST['SearchWhat'];
						$keyword=$_POST['searchText'];
						
						if(isset($_POST['SearchWhat']))	
							{
?>
								<div class="row form-group">
									<div class="col-sm-12 text-center">
										<label class="text-center"> Search Results</label>
									</div>
								</div>	
<?php								
								if($selectedSearchWhat=="Profile")
									{
										if($selectedSearchBy=="Friend")
								   			{	
								   				$stmt=pg_prepare($conn,"s","select * from sp_view_user_profile_friend_changed($1,$2)");
												$sqlname="s";
												$result=pg_execute($conn,"s",array($userName,$keyword));
								   				$rows=pg_num_rows($result);
								   				if ($rows>0)
								  					{	
								  					 	while ($row=pg_fetch_array($result,NULL,PGSQL_NUM))
															{
																
?>
																<div class="row" >
																	<div class="col-sm-2 ">
																		<input type="text" class="form-control" name="user" style="resize:none" readonly="" value="<?php echo ($row[0]);?>"></input>
																	</div>
																	<div class="col-sm-10">
																		
																	</div>
																</div>	
																<div class="row">	
																	<div class="col-sm-12">
																		<textarea class="form-control" readonly="" rows="3" style="resize:none" cols="50"><?php echo($row[1]);?> </textarea>
																	</div>
																</div>
<?php
															}
													}
												else 
													{
?>													
														<div class="row form-group">
															<div class="col-sm-12 text-center">
																<label class="text-center"> No Results found Matching Your criteria </label>
															</div>
														</div>
<?php												
													}		
												$SQL=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname));
								   				pg_query($SQL);

								   			}
							  		 	elseif($selectedSearchBy=="FriendOfFriend")
								   			{	
												$stmt5=pg_prepare($conn,"s","select * from sp_view_user_profile_friend_of_friend($1,$2)");
												$sqlname5="s";
												$result5=pg_execute($conn,"s",array($userName,$keyword));
								   				$rows5=pg_num_rows($result5);
								   				if ($rows5>0)
								  					{	
								  					 	while ($row5=pg_fetch_array($result5,NULL,PGSQL_NUM))
															{
								
?>
																<div class="row" >
																	<div class="col-sm-2 ">
																		<input type="text" class="form-control" name="user" style="resize:none" readonly="" value="<?php echo ($row5[0]);?>"></input>
																	</div>
																	<div class="col-sm-10">
																		
																	</div>
																</div>	
																<div class="row">	
																	<div class="col-sm-12">
																		<textarea class="form-control" readonly="" style="resize:none" rows="3" cols="50"><?php echo($row5[1]);?> </textarea>
																	</div>
																</div>
<?php
															}
													}
												else 
													{
?>													
														<div class="row form-group">
															<div class="col-sm-12 text-center">
																<label class="text-center"> No Results found Matching Your criteria </label>
															</div>
														</div>
<?php												
													}		
												$SQL5=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname5));
								   				pg_query($SQL5);									   		
								   			}
								   		elseif($selectedSearchBy=="Everyone")
								   			{	
												$stmt1=pg_prepare($conn,"s","select * from sp_view_user_profile_public($1)");
												$sqlname1="s";
												$result1=pg_execute($conn,"s",array($keyword));
								   				$rows1=pg_num_rows($result1);
								   				if ($rows1>0)
								  					{	
								  					 	while ($row1=pg_fetch_array($result1,NULL,PGSQL_NUM))
															{
?>
																<div class="row" >
																	<div class="col-sm-2 ">
																		<input type="text" class="form-control" name="user" readonly="" value="<?php echo ($row1[0]);?>"></input>
																	</div>
																	<div class="col-sm-10">
																		
																	</div>
																</div>	
																<div class="row">	
																	<div class="col-sm-12">
																		<textarea class="form-control" style="resize:none" readonly="" rows="3" cols="50"><?php echo($row1[1]);?> </textarea>
																	</div>
																</div>
<?php
															}
													}
												else 
													{
?>													
														<div class="row form-group">
															<div class="col-sm-12 text-center">
																<label class="text-center"> No Results found Matching Your criteria </label>
															</div>
														</div>
<?php												
													}	
												$SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname1));
								   				pg_query($SQL1);
								   			
								   			}	
					   				}
					   			elseif($selectedSearchWhat=="DiaryEntry")
					   				{
					   					if($selectedSearchBy=="Everyone")
					   						{
					   							$stmt2=pg_prepare($conn,"s","select * from sp_view_user_diary_public($1)");
												$sqlname2="s";
												$result2=pg_execute($conn,"s",array($keyword));
								   				$rows2=pg_num_rows($result2);
								   				if ($rows2>0)
								  					{	
								  					 	while ($row2=pg_fetch_array($result2,NULL,PGSQL_NUM))
															{
																$time_post=date("Y-M-d(g:i a)",strtotime($row2[3]));				
?>																

																<div class="panel panel-default">
																<div class="panel-body">
																	<div class="row">
																		<div class="col-sm-2">
																			<input class="form-control" type="text" name="user" style="resize:none" readonly="" value="<?php echo ($row2[0]);?>"></input>
																		</div>
																		<div class="col-sm-10">
																			
																		</div>
																	</div>
																	<div class="row">	
																		<div class="col-sm-4">
																			<input type="text" class="form-control " name="title" readonly="" value="<?php echo ($row2[1]);?>"></input>
																		</div>
																		<div class="col-sm-2">
																			<input class="form-control" style="resize:none" readonly="" value=" at <?php echo ($time_post);?>"></input>
																		</div class="col-sm-6">
																		<div ></div>
																	</div>
																	<div class="row">	
																		<div class="col-sm-12">
																			<textarea readonly="" rows="3" cols="50" class="form-control"><?php echo($row2[2]);?> </textarea>
																		</div>
																	</div>
																</div>
																</div>
<?php
															}
													}
												else 
													{
?>													
														<div class="row form-group">
															<div class="col-sm-12 text-center">
																<label class="text-center"> No Results found Matching Your criteria </label>
															</div>
														</div>
<?php												
													}		
												$SQL2=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname2));
								   				pg_query($SQL2);	
					   						}

					   					elseif($selectedSearchBy=="Friend")
					   						{
					   							$stmt3=pg_prepare($conn,"s","select * from sp_view_user_diary_entry_friend($1,$2)");
												$sqlname3="s";
												$result3=pg_execute($conn,"s",array($userName,$keyword));
								   				$rows3=pg_num_rows($result3);
								   				if ($rows3>0)
								  					{	
								  					 	while ($row3=pg_fetch_array($result3,NULL,PGSQL_NUM))
															{
																$time_post=date("Y-M-d(g:i a)",strtotime($row3[3]));			
?>																<div class="panel panel-default">
																<div class="panel-body">
																	<div class="row">
																		<div class="col-sm-2">
																			<input class="form-control" type="text" name="user" readonly="" value="<?php echo ($row3[0]);?>"></input>
																		</div>
																		<div class="col-sm-10">
																			
																		</div>
																	</div>
																	<div class="row">	
																		<div class="col-sm-4">
																			<input type="text" class="form-control " name="title" readonly="" value="<?php echo ($row3[1]);?>"></input>
																		</div>
																		<div class="col-sm-2">
																			<input class="form-control" style="resize:none" readonly="" value=" at <?php echo ($time_post);?>"></input>
																		</div class="col-sm-6">
																		<div ></div>
																	</div>
																	<div class="row">	
																		<div class="col-sm-12">
																			<textarea readonly="" rows="3" cols="50" class="form-control"><?php echo($row3[2]);?> </textarea>
																		</div>
																	</div>
																</div>
																</div>
<?php
															}
													}
												else 
													{
?>													
														<div class="row form-group">
															<div class="col-sm-12 text-center">
																<label class="text-center"> No Results found Matching Your criteria </label>
															</div>
														</div>
<?php												
													}		
												$SQL3=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname3));
								   				pg_query($SQL3);	
					   						}
					   					elseif ($selectedSearchBy=="FriendOfFriend")
					   						{
					   							$stmt4=pg_prepare($conn,"s","select * from sp_view_user_diary_entry_friend_updated($1,$2)");
												$sqlname4="s";
												$result4=pg_execute($conn,"s",array($userName,$keyword));
								   				$rows4=pg_num_rows($result4);
								   				if ($rows4>0)
								  					{	
								  					 	while ($row4=pg_fetch_array($result4,NULL,PGSQL_NUM))
															{
																$time_post=date("Y-M-d(g:i a)",strtotime($row4[3]));			
?>																<div class="panel panel-default">
																<div class="panel-body">
																	<div class="row">
																		<div class="col-sm-2">
																			<b><input class="form-control" type="text" name="user" readonly="" value="<?php echo ($row4[0]);?>"></input></b>
																			<p>				</p>
																		</div>
																		<div class="col-sm-10">
																			
																		</div>
																	</div>
																	<div class="row">	
																		<div class="col-sm-3">
																			<input type="text" class="form-control " name="title" readonly="" value="<?php echo ($row4[1]);?>"></input>
																		</div>
																		<div class="col-sm-6">
																			<textarea readonly="" rows="3" cols="50" style="resize:none" class="form-control"><?php echo($row4[2]);?> </textarea>
																		</div>
																		<div class="col-sm-2">
																			<input class="form-control" readonly="" style="resize:none" value="<?php echo ($time_post);?>"></input>
																		</div>
																	</div>
																</div>
																</div>	
<?php
															}
													}
												else 
													{
?>													
														<div class="row form-group">
															<div class="col-sm-12 text-center">
																<label class="text-center"> No Results found Matching Your criteria </label>
															</div>
														</div>
<?php												
													}		
												$SQL4=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname4));
								   				pg_query($SQL4);
					   						}					
					   				}
							}					
					}
			}
?>
	
	
</form>
</div>
</body>
</html>
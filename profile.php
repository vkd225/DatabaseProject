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
<!DOCTYPE>
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
				    <ul class="nav navbar-nav navbar-right">
				        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
				    </ul>
				  </div>
				</nav>
		</div>


<form method="post" action="profile.php">


		<div class="row">
			<div class="col-sm-12">
				<textarea  class="form-control" readonly=""><?php echo($profile);?></textarea>
			<div>
		</div >
		<div class="row">
			<div class="col-sm-2">
				<h4>Profile Comment</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-">
				<textarea class="form-control" name="comment"></textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-">
				<input type="submit" name="comment_button" value="comment"></input>
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
				<div class="col-sm-">
					<input type="text" class="form-control" name="commenter" readonly="" value="<?php echo ($row[0]);?>"></input>
				</div>
				<div class="col-sm-">
					<textarea class="form-control" readonly=""><?php echo($row[1]);?> </textarea>
				</div>
				<div class="col-sm-">
					<input type="text" class="form-control" name="time_posted_comment" readonly="" value="<?php echo ($time_post);?>"></input>
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
				<div class="col-sm-">
					<h3>Diary entry</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-">
					<input type="text" class="form-control" name="title" id="diary_title" placeholder="title" /></input>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-">
					<input type="text" class="form-control" name="body" id="diary_body" placeholder="body" /></input>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-">
					<input type="submit" name="post" value="post"></input>
				</div>
			</div>
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

							if (isset($_POST[$row[0]]))
								{

									if (isset($_POST[$row[0]."comment"]))
										{
											$body1=$_POST[$row[0]."comment"];
											$diaryentry_id=$row[0];
											$stmt7=pg_prepare($conn,"k","select sp_insert_user_diary_comment($1,$2,$3,$4)");
											$sqlname7="k";
									   		$result7=pg_execute($conn,"k",array($userName,$userName,$body1,$diaryentry_id));
									   		$SQL7=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname7));
				  						 	pg_query($SQL7);
		   									header("Location:profile.php");
				   						}
				   					break;

		   						}
		   				}

		   		}



			$SQL8=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname8));
		    pg_query($SQL8);
		}
?>
<?php
$stmt3=pg_prepare($conn,"s","select * from sp_post_diary_entry($1)");
	$sqlname3="s";
    $result3=pg_execute($conn,"s",array("$userName"));
    $rows3=pg_num_rows($result3);
    if ($rows3>0)
	   {
	   		while ($row3=pg_fetch_array($result3,NULL,PGSQL_NUM))
				{
					$time_post=date("Y-M-d(g:i a)",strtotime($row3[3]));
?>
					<div class="row">
						<div class="col-sm-3">
							<input type="text" class="form-control" name="title_" readonly="" value="<?php echo ($row3[1]);?>"></input>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-3">
							<textarea class="form-control" readonly=""><?php echo($row3[2]);?> </textarea>
						</div>

						<div class="col-sm-9">
							<input type="text" class="form-control" name="time_posted_comment" readonly="" value="<?php echo ($time_post);?>"></input>
						</div>
					</div>
					<?php

						$result4=pg_query("select * from sp_show_user_diary_comment_updated($row3[0])");
						$rows4=pg_num_rows($result4);
					    if ($rows4>0)
						   {
						   		while ($row4=pg_fetch_array($result4,NULL,PGSQL_NUM))
									{
										$time_post=date("Y-M-d(g:i a)",strtotime($row4[2]));
					?>
										<div class="row">
											<div class="col-sm-2">
												<input type="text" class="form-control" name="commenter" readonly="" value="<?php echo ($row4[0]);?>"></input>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-3">
												<textarea readonly="" class="form-control"><?php echo($row4[1]);?> </textarea>
											</div>
											<div class="col-sm-9">
												<input type="text" class="form-control" name="time_posted_comment" readonly="" value="<?php echo ($time_post);?>"></input>
											</div>
						  				</div>

						<?php
									}

							}
						else
							{
						?>
								<div class="row">
									<div class="col-sm-12">
										<label>No Comments</label>>
									</div>
								</div>
						<?php
							}
						?>

				  	<div class="row">
						<div class="col-sm-2">
							<input type="text" class="form-control" name="<?php echo $row3[0]."comment";?>" id="diary_body_comment" placeholder="comment"/></input>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-1">
							<input type="submit" class="btn btn-primary" name="<?php echo $row3[0]; ?>" value="diary_comment"></input>
						</div>
					</div>

<?php

		}
	$SQL3=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname3));
	pg_query($SQL3);

	}
?>

</form>
</body>
</html>

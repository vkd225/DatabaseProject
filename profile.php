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
</head>
<body>
<h1>Profile Page</h1>
<form method="post" action="profile.php">
	<table>
		<tr>
			<td>
				<textarea  readonly=""><?php echo($profile);?></textarea>
			<td>
		</tr>
		<tr>
			<td>
				<h3>Profile Comment</h3>
			</td>
		</tr>
		<tr>
			<td>
				<textarea  name="comment"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<input type="submit" name="comment_button" value="comment"></input>
			</td>
			</tr>

<?php
	$stmt2=pg_prepare($conn,"s","select * from sp_search_comments_by_commented_on($1)");
	$sqlname2="s";
	$result2=pg_execute($conn,"s",array("$userName"));
	$rows2=pg_num_rows($result2);
	   if ($rows2>0)
		   {
		   	while ($row=pg_fetch_array($result2,NULL,PGSQL_NUM))
				{
?>
			<tr>
				<td>
					<input type="text" name="commenter" readonly="" value="<?php echo ($row[0]);?>"></input>
				</td>
				<td>
					<textarea><?php echo($row[1]);?> </textarea>
				</td>
				<td>
					<input type="text" name="time_posted_comment" readonly="" value="<?php echo ($row[2]);?>"></input>
				</td>
		  	</tr>
<?php
				}
			}
	$SQL2=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname2));
	pg_query($SQL2);
?>
        <!--The diary entry-->
	        <tr>
				<td>
					<h3>Diary entry</h3>
				</td>
			</tr>
			<tr>
				<td>
					<input type="text" name="title" id="diary_title" placeholder="title" /></input>
				</td>
			</tr>
			<tr>
				<td>
					<input type="text" name="body" id="diary_body" placeholder="body" /></input>
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="post" value="post"></input>
				</td>
			</tr>
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
?>
					<tr>
						<td>
							<input type="text" name="title_" readonly="" value="<?php echo ($row[1]);?>"></input>
						</td>
						<td>
							<textarea><?php echo($row[2]);?> </textarea> 
						</td>
						<td>
							<input type="text" name="time_posted_comment" readonly="" value="<?php echo ($row[3]);?>"></input>
						</td>
				  	</tr>
				  	<tr>
						<td>
							<input type="text" name="<?php echo $row[0]."comment";?>" id="diary_body_comment" placeholder="comment"/></input>
						</td>
						<td>
							<input type="submit" name="<?php echo $row[0]; ?>" value="diary_comment"></input>
						</td>
					</tr>
						
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

			$rows8=pg_num_rows($result6);
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
?>
						<tr>
							<td><input type="text" name="commenter" readonly="" value="<?php echo ($row[0]);?>"></input></td>
							<td><textarea><?php echo($row[1]);?> </textarea> </td>
							<td><input type="text" name="time_posted_comment" readonly="" value="<?php echo ($row[2]);?>"></input></td>
		  				</tr>
<?php
					}
			}
		$SQL10=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname10));
		Pg_query($SQL10);
?>
	</table>
</form>
</body>
</html>

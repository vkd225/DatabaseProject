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
    $friend=$_SESSION['friend'];
	#profile from database
	$stmt=pg_prepare($conn,"s","select profile from user_profile where user_name=$1");
	$sqlname="s";
	$result=pg_execute($conn,$sqlname,array("$friend"));
	$rows=pg_num_rows($result);
	if ($rows>0)
	   {
	   	    echo "This page belongs to  " . $friend ;
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
			$stmt8=pg_prepare($conn,"s","select * from sp_post_diary_entry($1)");
			$sqlname8="s";
			$result8=pg_execute($conn,"s",array("$friend"));

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
									   		$result7=pg_execute($conn,"k",array($userName,$friend,$body1,$diaryentry_id));
									   		$SQL7=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname7));
				  						 	pg_query($SQL7);
		   									header("Location:friendsPage.php");
				   						}
				   					break;

		   						}	
		   				}

		   		}



			$SQL8=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname8));
		    pg_query($SQL8);
		}
?>

<!DOCTYPE>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<a href="profile.php">Profile</a>
<a href="settings.php">Settings</a>
<a href="friends.php">Friends</a>
<a href="search.php">Search</a>
<h1>Profile Page</h1>
<a href="friends.php">Friends</a>
<form method="post" action="friendsPage.php">
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
<?php
		$stmt2=pg_prepare($conn,"s","select * from sp_search_comments_by_commented_on($1)");
		$sqlname2="s";
		$result2=pg_execute($conn,"s",array("$friend"));
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
		}
?>
        <!--The diary entry-->
        <tr>
        	<td>
        		<h3>Diary Entries</h3>
        	</td>
        </tr>
<?php

	$stmt3=pg_prepare($conn,"s","select * from sp_post_diary_entry($1)");
	$sqlname3="s";
    $result3=pg_execute($conn,"s",array("$friend"));
    $rows3=pg_num_rows($result3);
    if ($rows3>0)
	   {
	   		while ($row3=pg_fetch_array($result3,NULL,PGSQL_NUM))
				{
?>
					<tr>
						<td>
							<input type="text" name="title_" readonly="" value="<?php echo ($row3[1]);?>"></input>
						</td>
						<td>
							<textarea><?php echo($row3[2]);?> </textarea> 
						</td>
						<td>
							<input type="text" name="time_posted_comment" readonly="" value="<?php echo ($row3[3]);?>"></input>
						</td>
					<?php

						$result4=pg_query("select * from sp_show_user_diary_comment_updated($row3[0])");
						$rows4=pg_num_rows($result4);
					    if ($rows4>0)
						   {
						   		while ($row4=pg_fetch_array($result4,NULL,PGSQL_NUM))
									{
					?>
										<tr>
											<td><input type="text" name="commenter" readonly="" value="<?php echo ($row4[0]);?>"></input></td>
											<td><textarea><?php echo($row4[1]);?> </textarea> </td>
											<td><input type="text" name="time_posted_comment" readonly="" value="<?php echo ($row4[2]);?>"></input></td>
						  				</tr>	
									
						<?php
									}
									
							}
						else
							{
						?>		
								<tr>
									<td>
										<label>No Comments</label>>
									</td>
								</tr>
						<?php
							}	
						?>	
									
				  	<tr>
						<td>
							<input type="text" name="<?php echo $row3[0]."comment";?>" id="diary_body_comment" placeholder="comment"/></input>
						</td>
						<td>
							<input type="submit" name="<?php echo $row3[0]; ?>" value="diary_comment"></input>
						</td>
					</tr>
						
<?php
				}
		}
	$SQL3=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname3));
	pg_query($SQL3); 
	
	
?> 


</table>
</form>
</body>
</html>
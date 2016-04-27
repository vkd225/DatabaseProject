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
   #$userName=$_SESSION['user'];
   	$userName='psk287';
?>

<<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<h1>The Search Page</h1>
<form method="Post" >
	<table>
			<tr>
				<td><label>Search</label></td>
				<td><input name="searchText" id="searchText" required></input></td>
			</tr>
			<tr>
			<tr>
			<td><label>Search What</label></td>
				<td><input type="radio" name="SearchWhat" id="Profile" value="Profile" > Profile </td>
 				<td><input type="radio" name="SearchWhat" id="DiaryEntry" value="DiaryEntry" > Diary Entry </td>
			</tr>
			<td><label>Search By</label></td>
				<td><input type="radio" name="SearchBy" id="Friend" value="Friend" > Friend </td>
 				<td><input type="radio" name="SearchBy" id="FriendOfFriend" value="FriendOfFriend" > Friend Of Friend </td>
 				<td><input type="radio" name="SearchBy" id="Everyone" value="Everyone" > Everyone </td>
			</tr>

			<tr><td><input type='submit' name='Search' value='Search'/></td>
			</tr>
			
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
																<tr>
																	<td>
																		<input type="text" name="user" readonly="" value="<?php echo ($row[0]);?>"></input>
																	</td>
																	<td>
																		<textarea><?php echo($row[1]);?> </textarea>
																	</td>
																</tr>
<?php
															}
													}
												$SQL=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname));
								   				pg_query($SQL);

								   			}
							  		 	elseif($selectedSearchBy=="FriendOfFriend")
								   			{	
									   		
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
																<tr>
																	<td>
																		<input type="text" name="user" readonly="" value="<?php echo ($row1[0]);?>"></input>
																	</td>
																	<td>
																		<textarea><?php echo($row1[1]);?> </textarea>
																	</td>
																</tr>
<?php
															}
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
?>
																<tr>
																	<td>
																		<input type="text" name="user" readonly="" value="<?php echo ($row2[0]);?>"></input>
																	</td>
																	<td>
																		<input type="text" name="title" readonly="" value="<?php echo ($row2[1]);?>"></input>
																	</td>
																	<td>
																		<textarea><?php echo($row2[2]);?> </textarea>
																	</td>
																	<td>
																		<input type="text" name="time_posted" readonly="" value="<?php echo ($row2[3]);?>"></input>
																	</td>
																</tr>
<?php
															}
													}
												$SQL2=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname2));
								   				pg_query($SQL2);	
					   						}

					   					if($selectedSearchBy=="Friend")
					   						{
					   							$stmt3=pg_prepare($conn,"s","select * from sp_view_user_diary_entry_friend_updated($1,$2)");
												$sqlname3="s";
												$result3=pg_execute($conn,"s",array($userName,$keyword));
								   				$rows3=pg_num_rows($result3);
								   				if ($rows3>0)
								  					{	
								  					 	while ($row3=pg_fetch_array($result3,NULL,PGSQL_NUM))
															{
?>
																<tr>
																	<td>
																		<input type="text" name="user" readonly="" value="<?php echo ($row3[0]);?>"></input>
																	</td>
																	<td>
																		<input type="text" name="title" readonly="" value="<?php echo ($row3[1]);?>"></input>
																	</td>
																	<td>
																		<textarea><?php echo($row3[2]);?> </textarea>
																	</td>
																	<td>
																		<input type="text" name="time_posted" readonly="" value="<?php echo ($row3[3]);?>"></input>
																	</td>
																</tr>
<?php
															}
													}
												$SQL3=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname3));
								   				pg_query($SQL3);	
					   						}				
					   				}
							}					
					}
			}
?>
	</table>
	
</form>
</body>
</html>
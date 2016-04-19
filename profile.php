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
		   #diary entry from database
		   $stmt=pg_prepare($conn,"s","select profile from user_profile where user_name=$1");
		   $sqlname="s";
		   $result=pg_execute($conn,$sqlname,array("$userName"));
		   $rows=pg_num_rows($result);
		   if ($rows>0)
		   {	
		   		$profile=pg_fetch_result($result, 0, 0);
		   }
		   else
		   {
		   	echo"No such record exists";
		   }
		   $SQL=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname));
		   pg_query($SQL);

		   #diary entry from database
			$stmt1=pg_prepare($conn,"s","select title,body,time_posted from user_diary where user_name=$1");
			$sqlname1="s";
		    $result1=pg_execute($conn,"s",array("$userName"));
		    $rows1=pg_num_rows($result1);
		   if ($rows1>0)
		   {	
		   		$diary=pg_fetch_array($result1,0,PGSQL_NUM);
		   		$title=$diary[0];
		   		$body=$diary[1];
		   		$time_posted=$diary[2];
		   }
		   else
		   {
		   	echo"No such record exists";
		   }		   
		   $SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname1));
		   pg_query($SQL1);



?>
<!DOCTYPE>
<<!DOCTYPE html>s 
<html>
<head>
	<title></title>
</head>
<body>
<h1>Profile Page</h1>
<a href="friends.php">friends</a>
<h5><?php echo"Welcome ". $userName ?> </h5>
<form method="post" action="profile.php">
<button type="submit" id="logout" name="logout">logout</button> 
	<table>
		<tr>
			<td>
				<textarea  readonly=""><?php echo($profile);?></textarea>
			<td>
		</tr>
		<tr>
			<td>
				<input type="text" name="title" id="title" readonly="" value="<?php echo($title);?>"></input>
			</td>
		</tr>
		<tr>
			<td>
				<input type="text" name="time_posted" id="time_posted" readonly="" value="<?php echo($time_posted);?>"></input>
			</td>
		</tr>
		<tr>
			<td>
				<textarea  readonly=""><?php echo($body);?></textarea>
			</td>
		</tr>
		
			<?php
			$stmt2=pg_prepare($conn,"s","select * from sp_search_comments_by_commented_on($1)");
			$sqlname2="s";
		   $result2=pg_execute($conn,"s",array("$userName"));
		   $rows2=pg_num_rows($result2);
		   if ($rows2>0)
		   {	while ($row=pg_fetch_array($result2,NULL,PGSQL_NUM))
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
		  #	   
		   $SQL2=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname2));
		   pg_query($SQL2);
			?>
		<tr>
		<td>	
		<h3>Comment here</h3>
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
		if($_SERVER['REQUEST_METHOD']=='POST')
		{
			if (isset($_POST["comment_button"])) 
			{
				
				if (isset($_POST["comment"]))
				{
				$commenter="ku336";						
				$Comment=$_POST["comment"];

				$stmt3=pg_prepare($conn,"s","select add_comment($1,$2,$3)");
				$sqlname3="s";
		   		$result3=pg_execute($conn,"s",array($userName,$commenter,$Comment));
		   		$SQL3=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname3));
		   		pg_query($SQL3);
		   		header("location:profile.php");

		   		}
		   	}	
		   	if (isset($_POST['logout'])) 
				{
					session_unset();
					session_destroy();
					header('location: login.php');
				}
		}   	 
		?>	
		
</table>
</form>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Registration Page</title>

</head>
	<body>
		<a href="login.php">LoGIN HERE</a>
		<h1> REGISTRATION PAGE</h1>
		<form id='registration' role='form' action='registration.php' method='post' >
			<table>
					<tr>
						<td>
							<label for='FirstName' >FirstName</label>
						</td>
						<td>
							<input type='text' name='FirstName' id='FirstName' placeholder='FirstName' maxlength="50" required/>
						</td>
					</tr>
					<tr>
						<td>
							<label for='LastName'>LastName</label>
						</td>
						<td>
							<input type='text' name='LastName' id='LastName' placeholder='LastName' maxlength="50" required/>
						</td>
					</tr>
					<tr>
						<td>
							<label for='UserName'>UserName</label>
						</td>
						<td>
							<input type='text' name='UserName' id='UserName' placeholder='UserName' maxlength="50" required/>
						</td>
					</tr>
					<tr>
						<td>
							<label for='Password'>Password</label>
						</td>
						<td>
							<input type='Password' name='Password' id='Password' placeholder='' required/>
						</td>
					</tr>
						<td>
							<label for='Age'>Age</label>
						</td>
						<td>
							<input type='number' name='Age' id='Age' min="1" max="100" placeholder='Age' required/>
						</td>
					<tr>
						<td>
							<label for='City'>City</label>
						</td>
						<td>
							<input type='text' name='City' id='City' placeholder='City' maxlength="50" required/>
						</td>
					</tr>
					<tr>
						<td>
							<input type='submit' name='Signup' value='Signup'/>
						</td>
					</tr>
			</table>


		</form>
<?php
 	if(isset($_POST["Signup"]))
   		{
		   $host        = "host=pdc-amd01.poly.edu";
		   $port        = "port=5432";
		   $dbname      = "dbname=ku336";
		   $credentials = "user=ku336 password=e0eycb7p";

		   $conn = pg_connect( "$host $port $dbname $credentials"  );
		   if(!$conn)
		   		{
		   	    	echo "Error : Unable to open database\n";
		   		}
		    else 
		    	{
		   		    echo "Opened database successfully\n";
		  		}
		   if($_SERVER["REQUEST_METHOD"]=="POST")
		   		{
			   		if(isset($_POST["UserName"]))
			   			{
						   $userName=$_POST["UserName"];
						   $stmt=pg_prepare($conn,"s","select user_name from users where user_name=$1");
						   $result=pg_execute($conn,"s",array("$userName"));
						   $rows=pg_num_rows($result);
						   if($rows>0)
						   		{
							  	 	echo"User name already exists , Please choose a different user name";
						   		}

						   else
							   {
				  					echo "hello";
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
									if(isset($_POST["Password"]))
										{
											$password= $_POST["Password"];
										}
									if(isset($_POST["Age"]))
										{
											$age= $_POST["Age"];
										}
									if(isset($_POST["City"]))
										{
											 $city= $_POST["City"];
											 echo $city;
										}
								}

						}
					$Insert_query=pg_query($conn,"select sp_signup('$firstName','$lastName','$userName','$password','$age','$city','1')");
				 	header('location: login.php');
		   		}
		}
?>
</body>
</html>
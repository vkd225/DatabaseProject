<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Registration Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
    	<div class="row">
  			<div class="span4"></div>
  			<div class="span4"><img class="center-block" src="tech_reg.png" /></div>
  			<div class="span4"></div>
		</div>

		<style type="text/css">
   			body { background: WhiteSmoke !important; }
		</style>

        <div class="row centered-form">
        	<h1 class="text-center">Registration Page</h1>

			<form id='registration' role='form' action='registration.php' method='post' >

	        	<div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
	        		<div class="panel panel-default">
	        			<div class="panel-heading">
				    		<h3 class="panel-title">Be a Techies Member <small>It's free!</small></h3>
				 		</div>
				 		<div class="panel-body">
				    		<form role="form">
				    			<div class="row">
				    				<div class="col-xs-6 col-sm-6 col-md-6">
				    					<div class="form-group">
				                			<input type="text" name="FirstName" id="FirstName" class="form-control input-sm" placeholder="First Name" maxlength="50" required/>
				    					</div>
				    				</div>
				    				<div class="col-xs-6 col-sm-6 col-md-6">
				    					<div class="form-group">
				    						<input type="text" name="LastName" id="LastName" class="form-control input-sm" placeholder="Last Name" maxlength="50" required/>
				    					</div>
				    				</div>
				    			</div>

				    			<div class="form-group">
				    				<input type="UserName" name="UserName" id="UserName" class="form-control input-sm" placeholder="Username" maxlength="50" required/>
				    			</div>

				    			<div class="row">
				    				<div class="col-xs-6 col-sm-6 col-md-6">
				    					<div class="form-group">
				    						<input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password" required/>
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
				                			<input type="text" name="City" id="City" class="form-control input-sm" placeholder="City" maxlength="50" required/>
				    					</div>
				    				</div>
				    				<div class="col-xs-6 col-sm-6 col-md-6">
				    					<div class="form-group">
				    						<input type="number" name="Age" id="Age" min="15" max="100" class="form-control input-sm" placeholder="Age" required/>
				    					</div>
				    				</div>
				    			</div>
				    			<input type="submit" value="Signup" name="Signup" id="Signup" class="btn btn-info btn-block"></input>
				    		</form>

				    		





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
	    
	   if($_SERVER["REQUEST_METHOD"]=="POST")
	   		{
	   		if(isset($_POST["Signup"]))
					{
		   		if(isset($_POST["UserName"]))
		   			{
					   $userName=$_POST["UserName"];
					   $stmt=pg_prepare($conn,"s","select user_name from users where user_name=$1");
					   $result=pg_execute($conn,"s",array("$userName"));
					   $rows=pg_num_rows($result);
					   if($rows>0)
					   		{
					   			$message="Username already exists Please choose a different user name";	
						  	 	echo "<script type='text/javascript'>alert('$message');</script>";
						  	 	#echo "<meta http-equiv='refresh' content='0;url=http://localhost/login.php'>";
					   		}
					   else
						   {
			  					
			    				if((isset($_POST["FirstName"]) and isset($_POST["LastName"]) and isset($_POST["UserName"])and isset($_POST["password"]) and isset($_POST["password_confirmation"]) and isset($_POST["Age"]) and isset($_POST["City"])))
									{
										$firstName= $_POST["FirstName"];
									
										$lastName= $_POST["LastName"];
									
										$userName= $_POST["UserName"];
									

										$password= $_POST["password"];
									
										$confirmpassword= $_POST["password_confirmation"];
									
										$age= $_POST["Age"];
									
										$city= $_POST["City"];
										 
									
											if (!($password==$confirmpassword))
												{
													$message = "Passwords dont match" ;
													echo "<script type='text/javascript'>alert('$message');</script>";	
												}	
											else
												{
													$Insert_query=pg_query($conn,"select * from sp_signup('$firstName','$lastName','$userName','$password','$age','$city','1')");
													$message = "You have successfully registered . Your username is ".$userName ;
													echo "<script type='text/javascript'>alert('$message');</script>";
													echo "<meta http-equiv='refresh' content='0;url=http://localhost/login.php'>";
												}
									}
							}
				
		   			}
			}
}			
?>
				    	</div>
		    		</div>
	    		</div>
	    	</form>
	    	<div class="row">
				<div class="col-sm-12 text-center">
				    <h3>Already a Member</h3>
				    <input type="submit" value="Login" name="Login" id="Login" class="btn btn-info btn-Primary"></input>
				    <p>					</p>
				    <p>					</p>
			</div>
    	</div>
    </div>
</body>
</html>
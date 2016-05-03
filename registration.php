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

											  				if(isset($_POST["password"]))
																{
																	if (isset($_POST["password_confirmation"]))
																		{
																			if (($_POST['password'])==($_POST['password_confirmation']))
																			 	{
																					$password=$_POST['password'];	
																				
														
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
																					
																					if(isset($_POST["Age"]))
																						{
																							$age= $_POST["Age"];
																						}
																					if(isset($_POST["City"]))
																						{
																							 $city= $_POST["City"];
																							 
																						}
																						$Insert_query=pg_query($conn,"select * from sp_signup('$firstName','$lastName','$userName','$password','$age','$city','1')");
																						$message = "You have been successfully registered. Your User name is ".$userName;
																						echo "<script type='text/javascript'>alert('$message');</script>";
											 											echo ("<meta http-equiv='refresh' content='0;url=http://localhost/login.php'>");
													
																				}
																			else
																				{
																					$message = "Passwords dont match";
																					echo "<script type='text/javascript'>alert('$message');</script>";
																				}	
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
    	</div>
    </div>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION["is_auth"]))
	{

    	header("location: login.php");
		exit;

	}
$host        = "host=pdc-amd01.poly.edu";
	$port        = "port=5432";
	$dbname      = "dbname=ku336";
	$credentials = "user=ku336 password=e0eycb7p";

	$conn = pg_connect( "$host $port $dbname $credentials"  );
	if(!$conn)
		{
	   		echo "Error : Unable to open database\n";
		}

	$keyword=$_SESSION['searchUser'];

?>
<!DOCTYPE html>
<html>
<head>
	<title> Friends </title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body data-spy="scroll" data-target=".navbar" data-spy="affix" data-offset="50">
	<div class="container">
		<div class="page-header text-center">
			<h1>Users</h1>
		</div>
		<div class="row">
			<nav class="navbar navbar-default">
  				<div class="container-fluid">
				    <div class="navbar-header">
				      <a class="navbar-brand" href="profile.php">Techies</a>
				    </div>
				    <ul class="nav navbar-nav">
				      <li><a href="profile.php">Profile</a></li>
				      <li><a href="search.php">Search</a></li>
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
<form id='searchuser' role='form' action='searchuser.php' method='post'>
		<div class="panel panel-default">
		<div class="panel-body">
<?php

	$stmt1=pg_prepare($conn,"s","select * from sp_search_users($1)");
	$sqlname1="s";
	$result1=pg_execute($conn,"s",array("$keyword"));
	$rows1=pg_num_rows($result1);
	if ($rows1>0)
		{
			while ($row=pg_fetch_array($result1,NULL,PGSQL_NUM))
				{

?>
				<div class="row">
					<div class="col-sm-1">
						<b><input type="submit"name="<?php echo $row[0]?>" style="background:none!important;border:none;padding:0!important;font: inherit; cursor: pointer" value="<?php echo $row[0]?>"></input></b>
					</div>
					<div class="col-sm-9">

					</div>
				</div>
				<p>			</p>
<?php
				}
		}
	else
		{
			echo "No results for your result";
		}
		  #
	$SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname1));
	pg_query($SQL1);
	if (isset($_POST["searchButton"]))
			{

				if (isset($_POST["searchUser"]))
					{
					# code...ec

						$_SESSION["searchUser"]=$_POST["searchUser"];
						echo ("<meta http-equiv='refresh' content='0;url=http://localhost/searchuser.php'>");
					}
			}
	if($_SERVER['REQUEST_METHOD']=='POST')
		{
			$stmt9=pg_prepare($conn,"s","select * from sp_search_users($1)");
            $sqlname9="s";
            $result9=pg_execute($conn,"s",array("$keyword"));

            $rows9=pg_num_rows($result9);
            if ($rows9>0)
                {
                    while ($row=pg_fetch_array($result9,NULL,PGSQL_NUM))
                        {

                            if (isset($_POST[$row[0]]))
                                {	$checkfriend=False;
                                	$checkfof=False;
                                	$_SESSION['searcheduser']=$row[0];
                                	$person=$_SESSION['searcheduser'];
                                	$userName=$_SESSION['user'];
                                	$result10=pg_query($conn,"select * from sp_show_friend('$userName')");
			    					$rows10=pg_num_rows($result10);

						            if ($rows10>0)
							            {
							            	while($row=pg_fetch_array($result10,NULL,PGSQL_NUM))
							            	{
							            		if ($person==$row[0])
							            		{
							            			$checkfriend=True;
							            			$_SESSION['friend']=$person;
							            			echo ("<meta http-equiv='refresh' content='0;url=http://localhost/friendsPage.php'>");
							            			break;
							            		}
							            	}
							            }


	                                $result11=pg_query($conn,"select * from sp_show_friend_of_friend('$userName')");
			    					$rows11=pg_num_rows($result11);
							        if ($rows11>0)
							            {
							            	while($row1=pg_fetch_array($result11,NULL,PGSQL_NUM))
						            			{
						            				if ($person==$row1[0])
						            					{
						            						$checkfof=True;

						            						echo ("<meta http-equiv='refresh' content='0;url=http://localhost/searchuserfriendfof.php'>");
						            					}
						            			}
							            }
							        if($checkfof!=True and $checkfriend!=True)
							            {

							        	    echo ("<meta http-equiv='refresh' content='0;url=http://localhost/searchuserfriendpublic.php'>");
							        	}
							        }

                        }

                }
            $SQL9=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname9));
            pg_query($SQL9);
        }

?>
</div>
</div>
</form>
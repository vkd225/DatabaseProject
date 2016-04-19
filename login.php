#I added a new comment
<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title> Login Page </title>
</head>
<body>
    <h1>Login page</h1>
    <form id="login" role="form" action="login.php" method="post">
    <table>
        <tr>
            <td><label for="username">Username</label></td>
            <td><input type="text" name="username" id="username" placeholder="username" maxlength="50" required/></td>
        </tr>
        <tr>
            <td><label for="password">Password</label></td>
            <td><input type="password" name="password" id="password" placeholder="password" required/></td>
        </tr>
        <tr>
            <td><input type="submit" name="login" value="login"/></td>
            <td></td>
        </tr>
		<tr>
        <td><a href="registration.php">Signup</a></td>
		</tr>
    </table>
    </form>
<?php
if(isset($_POST["login"]))
   {
   $host        = "host=pdc-amd01.poly.edu";
   $port        = "port=5432";
   $dbname      = "dbname=ku336";
   $credentials = "user=ku336 password=e0eycb7p";

   $conn = pg_connect( "$host $port $dbname $credentials"  );
   if(!$conn)
   {
      echo "Error : Unable to open database\n";
   } else {
      echo "Opened database successfully\n";
   }
   if($_SERVER["REQUEST_METHOD"]=="POST")
   {
	   if(isset($_POST["username"]))
	   {
		   $userName=$_POST["username"];
		   $password=$_POST["password"];
		   $stmt=pg_prepare($conn,"verify_login","select * from users where user_name=$1 and password=$2");
		   $result=pg_execute($conn,"verify_login",array($userName,$password));
		   $rows=pg_num_rows($result);
		   if($rows>0)
		   {
        $_SESSION['user']=$userName;
        
			header('Location: http://localhost/profile.php'); 

		   }
		
			else
			{
			echo"Wrong password or username";
			}
	   }
   }
   }
?>
</body>
</html>
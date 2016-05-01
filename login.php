
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Log In</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

  <body>
    <div class="container">
      <style type="text/css">
        body { background: WhiteSmoke !important; }
        .centered
        {
        text-align:center;  
        }
      </style>
      <div class="row centered-form">
        <h1 class="text-center">Welcome Back</h1> 
      </div> 
          
      <form id='login' role='form' action='login.php' method='post' >
        <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
          <div class="panel panel-default">
            <div class="panel-body">

              <div class="control-group">
                <label class="control-label"  for="username">Username:</label>
                  <div class="controls">
                    <input type="text" name="userName" id="userName" class="form-control input-sm" placeholder="Username" maxlength="50" required/>
                  </div>
              </div>

              <div class="control-group">
                <label class="control-label"  for="username">Passwrod:</label>
                  <div class="controls">
                    <input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password" maxlength="50" required/>
                    <p>               </p>
                  </div>
              </div>

              <div class="centered">
                <input type="submit" value="Login" class="btn btn-success"></input>
              </div>
            </div>
          </div>

          <div>
            <h5 class="text-center"> Not a Member Yet.</h5>
            <a class="text-center" href="registration.php">
              <div class="centered">
                <input type="submit" value="Sign Up" class="btn btn-info"></input> </a>
              </div>
          </div>
        </div>
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
                        $_SESSION['user'] = $userName;
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
    </div>
  </body>
</html>


<?php
session_start();
  $host        = "";
  $port        = "port=";
  $dbname      = "dbname=";
  $credentials = "user= password=";
  $conn = pg_connect( "$host $port $dbname $credentials"  );
    if(!$conn)
      {
        echo "Error : Unable to open database\n";
      }
      if($_SERVER["REQUEST_METHOD"]=="POST")
      {
      if (isset($_POST["Signup"]))
          {
            header('Location: http://localhost/registration.php');
          }
      }
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
                <label class="control-label"  for="userName">Username:</label>
                  <div class="controls">
                    <input type="text" name="userName" id="userName" class="form-control input-sm" placeholder="Username" maxlength="50" />
                  </div>
              </div>

              <div class="control-group">
                <label class="control-label"  for="password">Passwrod:</label>
                  <div class="controls">
                    <input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password" maxlength="50" />
                    <p>               </p>
                  </div>
              </div>

              <div class="centered">
                <input type="submit" value="Login" name="Login" id="Login" class="btn btn-success"></input>
              </div>
            </div>
          </div>

          <div>
            <h5 class="text-center"> Not a Member Yet.</h5>
            <a class="text-center" href="registration.php">
              <div class="centered">
                <input type="submit" value="Signup" name="Signup" id="Signup" class="btn btn-info"></input> </a>
              </div>
          </div>
        </div>
      </form>


      <?php
        if(isset($_POST["Login"]))
          {
            
            if($_SERVER["REQUEST_METHOD"]=="POST")
              {
                
                if(isset($_POST["userName"]))
                  {
                    
                    $userName=$_POST["userName"];
                    $password=$_POST["password"];
                    $stmt=pg_prepare($conn,"verify_login","select * from users where user_name=$1 and password=$2");
                    $result=pg_execute($conn,"verify_login",array($userName,$password));
                    $rows=pg_num_rows($result);
                    if($rows>0)
                      {
                        $_SESSION['user'] = $userName;
                        $_SESSION['is_auth']=True;
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

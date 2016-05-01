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
?>
<!DOCTYPE>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<h1>Edit Profile </h1>
<form method="POST" action="edit_profile.php">
    <table>
        <tr>
            <td>
                <input type="text" name="edit_profile" id="edit_profile" /></input>
            <td>
        </tr>
        <tr>
            <td>
                <input type="submit" name="edit" value="edit" /></input>
            </td>
        </tr>
        <?php
        if($_SERVER['REQUEST_METHOD']=='POST')
        {
            if (isset($_POST["edit"]))
                {
                  echo "i am in edit";
                    if (isset($_POST["edit_profile"]))
                        {
                            echo "I am in edit profile";
                            $body=$_POST["edit_profile"];
                            $stmt1=pg_prepare($conn,"s","select sp_edit_user_profile($1,$2)");
                            $sqlname1="s";
                            $result1=pg_execute($conn,"s",array($userName,$body));
                            $SQL1=sprintf('DEALLOCATE "%s"',pg_escape_string($sqlname1));
                            pg_query($SQL1);
                            header("location:edit_profile.php");
                        }
                }
        }
?>
</table>
</form>
</body>
</html>
<?php
session_start();
$host        = "host=pdc-amd01.poly.edu";
    $port        = "port=5432";
    $dbname      = "dbname=ku336";
    $credentials = "user=ku336 password=e0eycb7p";

    $conn = pg_connect( "$host $port $dbname $credentials"  );
    if(!$conn)
        {
            echo "Error : Unable to open database\n";
        }
 $id=$_SESSION['deltediaryid'];

		
		pg_query("select * from sp_delete_diary_entry('$id')");	
		unset($_SESSION['deltediaryid']);
		header("Location: profile.php");
?>
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
 $id=$_SESSION['deltediaryid'];

		pg_query("delete from comments_diaryentry where diaryentry_id='$id'");
		pg_query("select * from sp_delete_diary_entry('$id')");	
		unset($_SESSION['deltediaryid']);
		header("Location: profile.php");
?>

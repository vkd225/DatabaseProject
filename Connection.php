<?php
function connection()
   {
      $host        = "Enter Host";
      $port        = "Enter port";
      $dbname      = "Database Name";
      $credentials = "user= password= ";

      $conn = pg_connect( "$host $port $dbname $credentials"  );
      if(!$conn)
         {
            echo "Error : Unable to open database\n";
         }
   }
?>

<?php
function connection()
   {
      $host        = "host=pdc-amd01.poly.edu";
      $port        = "port=5432";
      $dbname      = "dbname=psk287";
      $credentials = "user=ku336 password=e0eycb7p";

      $conn = pg_connect( "$host $port $dbname $credentials"  );
      if(!$conn)
         {
            echo "Error : Unable to open database\n";
         }
   }
?>
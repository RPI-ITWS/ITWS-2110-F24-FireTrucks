<?php
   $servername = "localhost";
   $username = "phpmyadmin";  
   $password = "Marketplace18";      
   $dbname = "marketplace";
   
   $conn = new mysqli($servername, $username, $password, $dbname);
   
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
?>